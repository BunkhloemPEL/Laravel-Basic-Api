<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TestApiController;
use App\Http\Controllers\API\StudentApiController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get(uri: '/test', action: [TestApiController::class, 'test'])->name(name: 'test-api');

Route::apiResource(name: '/students', controller: StudentApiController::class);


