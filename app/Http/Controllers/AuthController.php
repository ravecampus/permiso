<?php

namespace App\Http\Controllers;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;
use Session;


class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
      
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|string'
        ]);
        $user = User::create([
            'name' => $request->name,
            'school_id'=>$request->school_id,
            'email'=>$request->school_id."@gmail.com",
            'password'=>bcrypt($request->password)
        ]);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function login(LoginRequest $request){
        $credentials = $request->getCredentials();

        if(!Auth::attempt($credentials)):
            $request->session()->regenerate();
            $errors = ['errors'=>['main' => ['Invalid School ID or Password!']]];
            return response()->json($errors,401);
        endif;

        $user = Auth::getProvider()->retrieveByCredentials($credentials);

        $a = Auth::user();
        return response()->json($a,200);
    }

    public function logout(){
        try {
            Session::flush();
             Auth::logout();
            $success = true;
            $message = 'Successfully logged out';
        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }
    
        // response
        $response = [
            'success' => $success,
            'message' => $message,
        ];
        return response()->json($response);
    }


    public function uploadSignature(Request $request){
        $file = $request->file('sigfile');
        $image = base64_encode(file_get_contents($file));
        $mimeType = $file->getClientMimeType();
        $size = $file->getSize();
        $name = $file->getClientOriginalName();

        $data = User::find($request->id);
        $data->digital_signature = $image;
        $data->ds_size = $size;
        $data->ds_type = $mimeType;
        $data->ds_orig_name = $name;
        $data->save();

        return response()->json($data, 200);
    }
}
