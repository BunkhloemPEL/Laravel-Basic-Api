<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blog_categories = BlogCategory::get();

        return response()->json(data: [
            'status'=>'success',
            'message'=>'Successfully retrieve data of blog categories.',
            'data'=>$blog_categories
        ],status:200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(data: $request->all() ,rules:[
            'name'=>'required'
        ]);

        if ($validator->fails()) {
            return response()->json(data:[
                'status'=>'failed',
                'message'=>'invalid input',
                'error'=>$validator->errors()
            ],status:400);
        }

        $data = $request->all();
        $data['slug']=Str::slug($data['name']);
        BlogCategory::create($data);

        return response()->json(data:[
            'status'=>'success',
            'message'=>'New blog category created successfully.'
        ], status: 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = BlogCategory::find($id);

        if($category) {
            return response()->json(data:[
                'status'=>'success',
                'data'=>$category
            ], status:200);
        }else{
            return response()->json(data:[
                'status'=>'failed',
                'message'=>"Blog category not found."
            ],status:404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make(data: $request->all(), rules:[
            'name'=>'required',
        ]);

        if ($validator->fails()) {
            return response()->json(data:[
                'status'=>'failed',
                'message'=>'invalid input',
                'error'=>$validator->errors()
            ],status:400);
        }

        $category = BlogCategory::find($id);

        if(!$category) {
            return response()->json(data:[
                'status'=>'failed',
                'message'=>"Blog category doesn't exist."
            ],status:404);
        }

        $category->name = $request->name;
        $category->slug = Str::slug($category->name);
        $category->save();

        return response()->json(data:[
                'status'=>'success',
                'message'=>'Category updated successfully',
                'data'=>$category
            ], status:200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = BlogCategory::find($id);

        if (!$category) {
            return response()->json(data:[
                'status'=>'failed',
                'message'=>"Blog category doesn't exist."
            ],status:404);
        }

        $name = $category->name;
        $category->delete();

        return response()->json(data:[
            'status'=>'Success',
            'message'=>"Category $name has been deleted."
        ],status:201);
    }
}
