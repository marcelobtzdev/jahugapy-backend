<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post("/login", "App\Http\Controllers\LoginController@login");
Route::post("/register", "App\Http\Controllers\UserController@store")->name('users.register');
Route::post('/validate-user', 'App\Http\Controllers\UserController@validateUser');
Route::post('/resend-validation-code/{user}', 'App\Http\Controllers\UserController@resendValidationCode');

Route::middleware('auth:sanctum')->group(function () {
    Route::get("/logout", "App\Http\Controllers\LoginController@logout");

    Route::apiResource('teams', 'App\Http\Controllers\TeamController')->except('show');
    Route::apiResource('users', 'App\Http\Controllers\UserController')->only('update');
});
