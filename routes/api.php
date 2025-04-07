<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\StudentController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\LectureController;
use App\Http\Controllers\PlanController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('students',[StudentController::class,'index']);
Route::post('students',[StudentController::class,'store']);
Route::get('students/{id}',[StudentController::class,'show']);
Route::put('students/{id}',[StudentController::class,'update']);
Route::delete('students/{id}',[StudentController::class,'destroy']);

Route::get('groups',[GroupController::class,'index']);
Route::post('groups',[GroupController::class,'store']);
Route::get('groups/{id}',[GroupController::class,'show']);
Route::put('groups/{id}',[GroupController::class,'update']);
Route::delete('groups/{id}',[GroupController::class,'destroy']);

Route::get('lectures',[LectureController::class,'index']);
Route::post('lectures',[LectureController::class,'store']);
Route::get('lectures/{id}',[LectureController::class,'show']);
Route::put('lectures/{id}',[LectureController::class,'update']);
Route::delete('lectures/{id}',[LectureController::class,'destroy']);

Route::post('plans', [PlanController::class, 'store']);
Route::get('plans/{id}', [PlanController::class, 'show']);
Route::put('plans/{id}', [PlanController::class, 'update']);
Route::delete('plans/{id}', [PlanController::class, 'destroy']);

Route::fallback(function () {
    return response()->json(['error' => 'Not Found!'], 404);
});
