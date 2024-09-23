<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::post('/register' , [UserController::class , 'register']);
Route::post('/login' , [UserController::class , 'login']);
Route::post('forgot-password' , [UserController::class , 'forgotPassword']);
Route::post('reset-password',[UserController::class , 'resetPassword']);

