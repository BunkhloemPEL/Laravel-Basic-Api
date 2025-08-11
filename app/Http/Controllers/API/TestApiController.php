<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TestApiController extends Controller
{
    public function test(){
        return response() -> json(data: [ 
            'data' => 'test',
            'status' => 'success'
        ]);
    }
}
