<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmploymentClassification;

class EmpClassificationController extends Controller
{
    public function leveling(){
        $data = EmploymentClassification::all();

        return response()->json($data, 200);
    }

    public function levelid(string $id){
        $data = EmploymentClassification::find($id);

        return response()->json($data, 200);
    }
}
