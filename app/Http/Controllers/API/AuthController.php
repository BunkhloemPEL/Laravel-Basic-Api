<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use function Pest\Laravel\json;

class AuthController extends Controller
{
    public function register(Request $request) {
        $validator = Validator::make(data: $request->all(), rules: [
            'name'=>'required|min:2',
            'email'=>'required|unique:users|email',
            'password'=>'required|min:8|confirmed',
            'password_confirmation'=> 'required|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json(data:[
                'status'=>'failed',
                'message'=>$validator->errors()
            ],status:400);
        }

        $data = $request->all();
        $name = $request->name;

        $file_path = null;
        if($request->hasFile('profile_picture') && $request->file(key: 'profile_picture')->isValid()) {
            //Save the file into a variable first to move it later on
            $file = $request->file(key: 'profile_picture');
            //Generate file name to store in our project it has to be unique
            $file_name = time().'_'.$file->getClientOriginalName();

            //Move it the file varible into the directory under the file name we generated
            $file->move(directory: public_path('storage/profile'), name: $file_name);

            //Store the file path to save it into our database under column "profile_picture"
            $file_path = "storage/profile".$file_name;
        }

        $data['profile_picture'] = $file_path;
        User::create($data);

        return response()->json(data:[
            'status'=>'success',
            'message' => "User $name is successfully created."
        ],status:201);
    }

    public function login(Request $request) {
        $validator = Validator::make(data: $request->all() ,rules:[
            'email'=>'required',
            'password'=>'required|min:8'
        ]);

        if ($validator->fails()){
            return response()->json(data:[
                'status'=>'failed',
                'message'=>$validator->errors()
            ],status:400);
        };

        if(Auth::attempt(credentials: ['email'=>$request->email, 'password'=>$request->password])){
            $user = Auth::user();

            $response['token'] = $user->createToken(name: 'BlogApp')->plainTextToken;
            $response['email']= $user->email;
            $response['name']= $user->name;

            return response()->json(data:[
                'status'=>'success',
                'message'=>"Login in successfully",
                'data'=> $response
            ], status:200);
        }else{
            return response()->json(data:[
                'status'=>'failed',
                'message'=>"Invalid Credentials.",
            ], status:400);

        }
    }

    public function profile() {
        $user = Auth::user();

        return response()->json(data: [
            'status'=>'success',
            'data'=>$user
        ],status:200);
    }

    public function logout() {
        $user = Auth::user();

        $user->tokens()->delete();
        return response()->json(data:[
            'status'=>'success',
            'message'=>'Logged out successfully.'
        ], status:200);
    }


}
