<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::post('/register' , [UserController::class , 'register']);
Route::post('/login' , [UserController::class , 'login']);
Route::post('forgot-password' , [UserController::class , 'forgotPassword']);
Route::post('reset-password',[UserController::class , 'resetPassword']);



Route::prefix('users')->group(function () {
    Route::group(['middleware' => 'jwt.auth'], function () {
        Route::post('refresh', [UserController::class, 'refresh']);
        Route::get('', [UserController::class, 'getUsers']);
        Route::post('/create', [UserController::class, 'create']);
        Route::put('/update/{userId}', [UserController::class, 'update_user']);
        Route::get('/delete/{userId}', [UserController::class, 'destroy']);
    });
});
