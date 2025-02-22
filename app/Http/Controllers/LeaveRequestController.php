<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveApplication;
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
        ->where("status", 1)
        ->orWhere("status", 2)->latest()->paginate(5);

        return response()->json($data, 200);
    }

    public function initialApproved(Request $request){
        $data =  LeaveApplication::find($request->id);
        $data->status = 1;
        $data->initial_appr_date = Carbon::now();
        $data->save();
       return response()->json($data, 200);
    }
    public function finalApproved(Request $request){
        $data =  LeaveApplication::find($request->id);
        $data->status = 2;
        $data->final_appr_date = Carbon::now();
        $data->save();
       return response()->json($data, 200);
    }
    public function disapproved(Request $request){
        $data =  LeaveApplication::find($request->id);
        $data->status = 3;
        $data->disapproved_date = Carbon::now();
        $data->save();
       return response()->json($data, 200);
    }
}
