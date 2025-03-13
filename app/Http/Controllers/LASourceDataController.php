<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;
use App\Models\LevelLeavecredit;
use App\Models\User;
use App\Models\InitialApproval;
use App\Models\FinalApproval;
use Illuminate\Support\Facades\Auth;

class LASourceDataController extends Controller
{
    public function leave(){
            $user = Auth::user();
            $data = LevelLeavecredit::select("level_leavecredit.*", "leave.description as leave_description")
                ->join('leave', 'leave.id', "=", "level_leavecredit.leave_id")
                ->where("emp_class_id",$user->emp_class_id )->get();

            return response()->json($data, 200);
    }
    public function leaveId($id){
        $data = LevelLeavecredit::select("level_leavecredit.*", "leave.description as leave_description")
            ->join('leave', 'leave.id', "=", "level_leavecredit.leave_id")
            ->where("emp_class_id",$id )->get();

        return response()->json($data, 200);
    }


    public function initialApproval(){
        $user = Auth::user();
        $data =  InitialApproval::select("initial_approvals.*", "users.name as initial")
        ->join('users', 'users.id', "=", "initial_approvals.user_id")
        ->where("initial_approvals.emp_class_id",$user->emp_class_id )->get();
        return response()->json($data, 200);
    }
    public function FinalApproval(){
        $user = Auth::user();
        $data =  FinalApproval::select("final_approvals.*", "users.name as final")
        ->join('users', 'users.id', "=", "final_approvals.user_id")
        ->where("final_approvals.emp_class_id",$user->emp_class_id )->get();
        return response()->json($data, 200);
    }
}
