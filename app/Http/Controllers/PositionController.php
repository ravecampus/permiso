<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Position;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Position::query();
        if($request->search != ''){
            $data = $data->where('description','like','%'.$request->search.'%');
        }
        $data = $data->latest()->paginate(5);

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
            'position_name' => 'required|string'
        ]);

        $data = Position::create([
            'description' => $request->position_name
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
            'position_name' => 'required|string'
        ]);

        $data = Position::find($id);
        $data->description = $request->position_name;
        $data->save();

        return response()->json($data, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Position::find($id);
        $data->delete();

        return response()->json($data, 200);
    }

    public function positionList(){
        $data = Position::orderBy("description", "asc")->get();
        return response()->json($data, 200);
    }
}
