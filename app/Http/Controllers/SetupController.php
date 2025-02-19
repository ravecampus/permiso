<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InitialApproval;
use App\Models\FinalApproval;
use App\Models\Leave;
use App\Models\LevelLeavecredit;
use App\Models\SchoolYear;

class SetupController extends Controller
{
    public function addInitialAppproval(Request $request){

        $chck = InitialApproval::where('user_id', $request->user_id)->where('emp_class_id', $request->emp_class_id)->first();
        if(!isset($chck)){
            $data = InitialApproval::create([
                'user_id' => $request->user_id,
                'name' => $request->name,
                'emp_class_id' => $request->emp_class_id 
            ]);
    
            return response()->json($data, 200);
        }else{
            return response()->json([], 422);
        }
       
    }

    public function listInitial(Request $request, string $id){
        $data = InitialApproval::query();
        if($request->search != ''){
            $data = $data->where('name','like','%'.$request->search.'%');
        }
        $data = $data->where('emp_class_id', $id)->latest()->get();

        return response()->json($data, 200);
    }


    public function delete(string $id){
        $data = InitialApproval::find($id);
        $data->delete();

        return response()->json($data, 200);
    }


    public function addFinalAppproval(Request $request){

        $chck = FinalApproval::where('user_id', $request->user_id)->where('emp_class_id', $request->emp_class_id)->first();
        if(!isset($chck)){
            $data = FinalApproval::create([
                'user_id' => $request->user_id,
                'name' => $request->name,
                'emp_class_id' => $request->emp_class_id 
            ]);
    
            return response()->json($data, 200);
        }else{
            return response()->json([], 422);
        }
       
    }

    public function listFinal(Request $request, string $id){
        $data = FinalApproval::query();
        if($request->search != ''){
            $data = $data->where('name','like','%'.$request->search.'%');
        }
        $data = $data->where('emp_class_id', $id)->latest()->get();

        return response()->json($data, 200);
    }


    public function deleteFinal(string $id){
        $data = FinalApproval::find($id);
        $data->delete();

        return response()->json($data, 200);
    }

    public function leave(){
        $data = Leave::all();

        return response()->json($data, 200);
    }


    public function addCredit(Request $request)
    {
        if($request->is_no_limit){
            $request->validate([
                "leave" => "required",
            ]);
        }else{
            $request->validate([
                "leave" => "required",
                "credits" => "required",
            ]);
        }

        $chk = LevelLeavecredit::where("leave_id", $request->leave)
                ->where("emp_class_id", $request->emp_class_id)->first();
     
        if(!isset($chk)){
            $lev = Leave::find($request->leave);
            $data = LevelLeavecredit::create([
                'leave_id' => $request->leave,
                'emp_class_id' =>$request->emp_class_id,
                'credits'=>$request->is_no_limit ? 0 : $request->credits,
                'is_no_limit'=>$request->is_no_limit ? 1 : 0,
                'description'=>$lev->description
            ]);
            return response()->json($data, 200);
        }else{
            return response()->json([], 400);
        }
    }

    public function listCredit(string $id){
        $data = LevelLeavecredit::where('emp_class_id', $id)->get();

        return response()->json($data, 200);
    }

    public function editCredit(Request $request, string $id){
        if(!$request->is_no_limit){
            $request->validate([
                "credits" => "required",
            ]);
        }
        $data = LevelLeavecredit::find($id);
        $data->credits = $request->is_no_limit ? 0 : $request->credits;
        $data->is_no_limit = $request->is_no_limit ? 1 : 0;
        $data->save();

        return response()->json($data, 200);
    }


    public function schoolyearSetup(Request $request){
        $request->validate([
            'start'=>'required'
        ]);

        $chk = SchoolYear::where('start', $request->start)->first();

        if(!isset($chk)){
            $data = SchoolYear::create([
                'start' => $request->start,
                'end' => $request->end,
            ]);
    
            return response()->json($data, 200);
        }else{
            return response()->json([], 409);
        }
        
    }

    public function schoolyearSetupEdit(Request $request, string $id){
        $request->validate([
            'start'=>'required'
        ]);

        $chk = SchoolYear::where('start', $request->start)->first();

        if(!isset($chk)){
            $data = SchoolYear::find($id);
                $data->start = $request->start;
                $data->end = $request->end;
                $data->save();
        
            return response()->json($data, 200);
        }else{
            return response()->json([], 409);
        }
    }

    public function schoolyearSetupList(Request $request){
  
        $data = SchoolYear::all();
         
        return response()->json($data, 200);
    }

    public function schoolyearSetupStatus(string $id){
        $data = SchoolYear::where('is_active', 1)->first();
        if(isset($data)){
            $data->is_active = 0;
            $data->save();
        }
      
        $stat = SchoolYear::find($id);
        $stat->is_active = 1;
        $stat->save();

        return response()->json($stat, 200);
    }


}
