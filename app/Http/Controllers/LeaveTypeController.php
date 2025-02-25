<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leave;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Leave::query();
        if($request->search != ''){
            $data = $data->where('description','like','%'.$request->search.'%');
        }
        $data = $data->orderBy('created_at', 'asc')->paginate(10);

        return response()->json($data, 200);
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
        $request->validate([
            'description' => 'required|string'
        ]);
        $data = Leave::create([
            "description" => $request->description
        ]);

        return response()->json($data, 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        $request->validate([
            "description" => "required|string"
        ]);
        $data = Leave::find($id);
        $data->description = $request->description;
        $data->save();

        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Leave::find($id);
        $data->delete();
        return response()->json($data, 200);
    }
}
