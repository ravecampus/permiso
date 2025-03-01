<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveApplication;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LeaveRequestController extends Controller
{
    public function initialRequest(Request $request){
        $user = Auth::user();
        $data = LeaveApplication::select('leave_application.*', 'users.name', 'users.image','leave.description')
                    ->join("users", "users.id", "=", "leave_application.user_id")
                    ->join("leave", "leave.id", "=","leave_application.leave_id")
                    ->where("leave_application.initial_appr_id", $user->id);
        if($request->search != ''){
            $data = $data->where('users.name','like','%'.$request->search.'%');
        }
        $data = $data->latest()->paginate(5);

        return response()->json($data, 200);
    }

    public function finalRequest(Request $request){
        $user = Auth::user();
        $data = LeaveApplication::select('leave_application.*', 'users.name', 'users.image','leave.description')
                    ->join("users", "users.id", "=", "leave_application.user_id")
                    ->join("leave", "leave.id", "=","leave_application.leave_id")
                    ->where("leave_application.final_appr_id", $user->id);
        if($request->search != ''){
            $data = $data->where('users.name','like','%'.$request->search.'%');
        }

        $data = $data
        // ->where("status", 1)
        ->whereIn("status", [1,2,3])->latest()->paginate(5);

        return response()->json($data, 200);
    }

    public function initialApproved(Request $request){
        $user = Auth::user();
        $data =  LeaveApplication::find($request->id);
        $data->status = 1;
        $data->initial_appr_date = Carbon::now();
        $data->save();
        Notification::create([
            "sender_id" => $user->id,
            "receiver_id" => $data->user_id,
            "leave_application_id" => $data->id,
            "status" => 0,
            "message" => "You have been initially approved on your leave application!"
        ]);

        Notification::create([
            "sender_id" => $data->user_id,
            "receiver_id" => $data->final_appr_id,
            "leave_application_id" => $data->id,
            "status" => 0,
            "message" => "You have notification for leave request!"
        ]);
       return response()->json($data, 200);
    }
    public function finalApproved(Request $request){
        $user = Auth::user();
        $data =  LeaveApplication::find($request->id);
        $data->status = 2;
        $data->final_appr_date = Carbon::now();
        $data->save();
        Notification::create([
            "sender_id" => $user->id,
            "receiver_id" => $data->user_id,
            "leave_application_id" => $data->id,
            "status" => 0,
            "message" => "You have been approved on your leave application!"
        ]);

       return response()->json($data, 200);
    }
    public function disapproved(Request $request){
        $user = Auth::user();
        $data =  LeaveApplication::find($request->id);
        $data->status = 3;
        $data->remarks = isset($request->remarks) ? $request->remarks : null;
        $data->disapproved_date = Carbon::now();
        $data->save();
        Notification::create([
            "sender_id" => $user->id,
            "receiver_id" => $data->user_id,
            "leave_application_id" => $data->id,
            "status" => 0,
            "message" => "You have been disapproved on your leave application!"
        ]);
       return response()->json($data, 200);
    }

    public function initialCount(){
        $user = Auth::user();
        $data = LeaveApplication::where("initial_appr_id", $user->id)->where("status", 0)->get();
        return response()->json($data, 200);
    }

    public function finalCount(){
        $user = Auth::user();
        $data = LeaveApplication::where("final_appr_id", $user->id)->where("status", 1)->get();
        return response()->json($data, 200);
    }
}
