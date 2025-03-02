<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Session;

class EmployeeController extends Controller
{

    public function storeEmployee(Request $request){

        $passdef = $request->default_password;
        if($passdef){
            $request->validate([
                'name'=>'required|string',
                'school_id'=>'required|unique:users,school_id',
                'position'=>'required',
                'office'=>'required',
                'date_of_employment'=>'required',
                'employee_classification'=>'required',
                'role'=>'required',
            ]);
        }else{
            $request->validate([
                'name'=>'required|string',
                'school_id'=>'required|unique:users,school_id',
                'position'=>'required',
                'office'=>'required',
                'date_of_employment'=>'required',
                'employee_classification'=>'required',
                'role'=>'required',
                'password' => 'required|string|min:4|confirmed',
            ]);
        }
        
        $user = User::create([
            'name' => $request->name,
            'position_id' => $request->position,
            'office_id' => $request->office,
            'role' => $request->role,
            'date_of_employment' =>Carbon::parse($request->date_of_employment)->format('Y-m-d'),
            'emp_class_id' => $request->employee_classification,
            'school_id'=>$request->school_id,
            'email'=>$request->school_id."@gmail.com",
            'password'=> $passdef ? bcrypt('1234') : bcrypt($request->password)
        ]);
    }

    public function updateEmployee(Request $request, string $id){
        $request->validate([
            'name'=>'required|string',
            // 'school_id'=>'required|unique:users,school_id',
            'position'=>'required',
            'office'=>'required',
            'date_of_employment'=>'required',
            'employee_classification'=>'required',
            'role'=>'required',
        ]);

        $data = User::find($id);
        $data->name = $request->name;
        // $data->school_id = $request->school_id;
        $data ->position_id = $request->position;
        $data->office_id = $request->office;
        $data->role = $request->role;
        $data->date_of_employment = Carbon::parse($request->date_of_employment)->format('Y-m-d');
        $data->emp_class_id = $request->employee_classification;
        $data->save();

        return response()->json($data, 200);

    }
    public function employee(Request $request){
        $data = User::query();
        if($request->search != ''){
            $data = $data->where('name','like','%'.$request->search.'%');
        }
        $data = $data->latest()->paginate(10);

        return response()->json($data, 200);
    }

    public function employeeList(Request $request){
        $data = User::with('empleave_credit');
        if($request->search != ''){
            $data = $data->where('name','like','%'.$request->search.'%')
            ->orWhere('school_id','like','%'.$request->search.'%');
        }

        if($request->filter != 0){
            $data = $data->where('office_id', $request->filter);
        }
        $data = $data->orderBy('name', 'asc')->paginate(10);

        return response()->json($data, 200);
    }

    public function changePasswordEmployee(Request $request, string $id){
        $user = Auth::user();
        $request->validate([
            'password' => 'required|string|min:4|confirmed',
            'password_confirmation' => 'required',
        ]);

        $data = User::find($id);
        $data->password = bcrypt($request->password);
        $data->save();

        if($data->id == $user->id){
            Session::flush();
            Auth::logout();
        }

        return response()->json($data, 200);
    }

    public function changeName(Request $request, string $id){
        $request->validate([
            "name" => "required|string"
        ]);
        $user = User::find($id);
        $user->name = $request->name;
        $user->save();

        return response()->json($user, 200);
    }

}
