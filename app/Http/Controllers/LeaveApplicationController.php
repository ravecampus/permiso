<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveApplication;
use App\Models\LevelLeavecredit;
use App\Models\myLeave;
use App\Models\SchoolYear;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class LeaveApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if($request->leave == 4){
            $file = $request->file('attachment');
            $image = base64_encode(file_get_contents($file));
            $mimeType = $file->getClientMimeType();
            $size = $file->getSize();
            $name = $file->getClientOriginalName();
        }
        $user = Auth::user();
        $request->validate([
            "leave" => "required",
            "date_apply" => "required",
            "cause" => "required",
            "number_of_day" => "required",
            "from" => "required",
            "until" => "required",
            "from_extension" => "required",
            "until_extension" => "required",
            "initial_approval" => "required",
            "final_approval" => "required",
        ]);
        $lev = LevelLeavecredit::where("emp_class_id", $request->emp_class_id)
                                ->where("leave_id", $request->leave)->first();
        $data = LeaveApplication::create([
            "user_id" => $user->id,
            "leave_id" => $request->leave,
            "cause" => $request->cause,
            "number_of_day" => $request->number_of_day,
            "date_apply" =>Carbon::parse(strstr($request->date_apply, " (", true))->format('Y-m-d'),
            "from" => Carbon::parse(strstr($request->from, " (", true))->format('Y-m-d'),
            "until" =>Carbon::parse(strstr($request->until, " (", true))->format('Y-m-d'),
            "from_ext" =>$request->from_extension,
            "until_ext" =>$request->until_extension,
            "initial_appr_id" =>$request->initial_approval,
            "final_appr_id" =>$request->final_approval,
            "emp_class_id" =>$request->emp_class_id,
            "leave_credit_id" =>$lev->id,
            "status" => 0,
            "school_year_id" => SchoolYear::where('is_active', 1)->first()->id ,
            "sick_attach" => $request->leave == 4 ? $image : null,
            "sa_size" => $request->leave == 4 ? $size : null,
            "sa_type" =>$request->leave == 4 ?  $mimeType : null,
            "sa_orig_name" => $request->leave == 4 ? $name :null,

        ]);

        return response()->json($data, 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = LeaveApplication::with('schoolyear', 'initial', 'final', 'signature')->select("leave_application.*", "leave.description as description")
        ->join("leave", "leave.id", "=", "leave_application.leave_id")
        ->where("leave_application.id", $id)->first();

        return response()->json($data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if($request->leave == 4 && isset($request->attachment)){
            $file = $request->file('attachment');
            $image = base64_encode(file_get_contents($file));
            $mimeType = $file->getClientMimeType();
            $size = $file->getSize();
            $name = $file->getClientOriginalName();
        }
        $user = Auth::user();
        $request->validate([
            "leave" => "required",
            "date_apply" => "required",
            "cause" => "required",
            "number_of_day" => "required",
            "from" => "required",
            "until" => "required",
            "from_extension" => "required",
            "until_extension" => "required",
            "initial_approval" => "required",
            "final_approval" => "required",
        ]);
        $lev = LevelLeavecredit::where("emp_class_id", $request->emp_class_id)
                                ->where("leave_id", $request->leave)->first();

        $data = LeaveApplication::find($id);
        $data->leave_id = $request->leave;
        $data->date_apply = Carbon::parse($request->date_apply)->format('Y-m-d');
        $data->cause = $request->cause;
        $data->from  = Carbon::parse($request->from)->format('Y-m-d');
        $data->until = Carbon::parse($request->until)->format('Y-m-d');
        $data->from_ext = $request->from_extension;
        $data->until_ext = $request->until_extension;
        $data->initial_appr_id = $request->initial_approval;
        $data->final_appr_id = $request->final_approval;
        $data->emp_class_id = $request->emp_class_id;
        $data->leave_credit_id = $lev->id;
        if($request->leave == 4 && isset($request->attachment)){
            $data->sick_attach = $image;
            $data->sa_size = $size;
            $data->sa_type = $mimeType;
            $data->sa_orig_name = $name;
        }
        $data->save();

        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = LeaveApplication::find($id);
        $data->delete();
        return response()->json($data, 200);
    }

    public function myLeave(Request $request){
        $user = Auth::user();
        $data = LeaveApplication::with('schoolyear', 'initial', 'final')->select("leave_application.*", "leave.description as description")
                ->join("leave", "leave.id", "=", "leave_application.leave_id")
                ->where("leave_application.user_id", $user->id);
        $data = $data->latest()->paginate(5);
        return response()->json($data, 200);
    }
}
