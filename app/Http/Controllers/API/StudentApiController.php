<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Attribute;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Console\Migrations\StatusCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::get();

        return response() -> json(data:[
            "status" => "success",
            "data" => $students,
        ], status: 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //Validator
        $validator = Validator::make(data: $request->all(), rules:[
            'name' => 'required|min:4',
            'email' => 'required|unique:students,email', //unique:students,email → the email must be unique in the students table's email column.
            'gender' => 'required|in:male,female' // in:male,female,others tells Laravel: Only accept the value if it exactly matches one of these: male, female, or others.
        ]);

        if ($validator->fails()) {
            return response() -> json(data:[
                'status' => 'failed',
                'error' => $validator->errors()
            ], status:400);
        };

        $data = $request->all();

        Student::create($data); //this will store the request data in the table students in our database

        return response()->json(data:[
            'status' => 'success',
            'message' => 'Student created successfully'
        ], status:201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //find student with $id
        $student = Student::find(id: $id);

        if ($student) {
            return response()->json(data:[
                'status'=> 'success',
                'data' => $student
            ], status:200);
        }

        return response()->json(data:[
            'status'=>'failed',
            'message'=>"Student with id: $id not found" //Note : to display a variable inside string you have to use double quote
        ], status:404);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //Validate
        $validator = Validator::make(data:$request->all(), rules:[
            'name'=>'required|min:4',
            'email'=>'required|unique:students,email,'.$id,
            'gender'=>'required|in:male,female'
        ]);

        if($validator->fails()) {
            return response()->json(data:[
                'status'=>'failed',
                'error'=>$validator->errors()
            ],status:400);
        }

        //Find if student we want to update exist
        $student = Student::find($id);

        if (!$student) {
            return response()->json(data:[
                'status'=>'failed',
                'message'=>"Student with id: $id not found"
            ], status:404);
        }

        //Update student
        $student->name = $request->name;
        $student->email = $request->email;
        $student->gender = $request->gender;
        $student->save();

        return response()->json(data:[
            'status'=>'success',
            'message' => 'student data updated',
            'data'=>$student
        ],status:200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //Search for student with $id
        $student = Student::find($id);
        if (!$student) {
            return response()->json(data:[
                'status'=>'failed',
                'message'=> "Student with id $id not found."
            ],status:404);
        }

        $name = $student->name;
        $student->delete();

        return response()->json(data:[
            'status'=>'Success',
            'message'=>"Student $name has been deleted."
        ],status:201);
    }
}
