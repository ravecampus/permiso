<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveApplication;
use App\Models\Leave;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function leaveHistory(){
        $user = Auth::user();
        $data = LeaveApplication::select("leave_application.*", "leave.description as description", "users.name")
                ->join("leave", "leave.id", "=", "leave_application.leave_id")
                ->join("users", "users.id", "=", "leave_application.user_id")
                ->where("leave_application.user_id", $user->id)
                ->orWhere("leave_application.initial_appr_id", $user->id)
                ->orWhere("leave_application.final_appr_id", $user->id)->whereIn("leave_application.status", [1,2,3])->latest()->get();

        return response()->json($data, 200);

    }
}
