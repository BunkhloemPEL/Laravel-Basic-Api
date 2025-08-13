<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BlogCategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\TestApiController;
use App\Http\Controllers\API\StudentApiController;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get(uri: '/test', action: [TestApiController::class, 'test'])->name(name: 'test-api');

Route::apiResource(name: '/students', controller: StudentApiController::class);

Route::post(uri: '/auth/register', action: [AuthController::class, 'register'])->name(name: 'register');
Route::post(uri: '/auth/login', action: [AuthController::class, 'login'])->name('login');

Route::group(['middleware'=>'auth:sanctum'],function() {
    Route::get(uri: '/profile', action: [AuthController::class, 'profile'])->name(name: 'profile');
    Route::get(uri: '/auth/logout', action: [AuthController::class, 'logout'])->name(name:'logout');
    Route::apiResource(name: '/category', controller: BlogCategoryController::class);
});

Route::get(uri: '/category', action: [BlogCategoryController::class, 'index'])->name(name:'category');


