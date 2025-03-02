<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LeaveApplication;
use Carbon\Carbon;

class LeaveReportController extends Controller
{
    public function leaveReport(Request $request){
        $from = Carbon::parse($request->datefrom)->format('Y-m-d');
        $to = Carbon::parse($request->dateto);
        $to->addDays(1)->format('Y-m-d');

      
        $data = LeaveApplication::with("signature")->select("leave_application.*", "leave.description as description")
                ->join("leave" , "leave.id", "=", "leave_application.leave_id");
                
        if($request->datefrom != "" && $request->dateto != ""){
            $data = $data->whereDate("date_apply",">=", $from)->whereDate("date_apply","<=", $to);
        }
        if($request->filter > 0){
            $data = $data->where("leave_application.leave_id", $request->filter);
        }
        $data_ = $data->get();
        $data__ = $data->latest()->paginate(10);
        

        return response()->json(["table"=>$data__, "print"=>$data_], 200);
    }
}
