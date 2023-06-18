<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SignupController;
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

// We can get the profile by this Route for this we have to pass Bearer Token
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// 1st method of routing 
// Route::post('/signup', [SignupController::class,'signup']);
// Route::post('/login', [SignupController::class,'login']);

// 2nd method of routing by grouping 
Route::controller(SignupController::class)->group(function(){
    Route::post('signup','signup');
    Route::post('login','login'); 
    Route::post('/update/{id}','update');
    Route::delete('/delete/{id}','delete');
});

Route::post('/logout', [SignupController::class,'logout'])->middleware('auth:sanctum');