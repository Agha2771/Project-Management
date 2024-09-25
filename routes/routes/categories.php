<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;


Route::prefix('categories')->group(function () {
    Route::get('', [CategoryController::class, 'index']);
    Route::get('/delete/{category}', [CategoryController::class, 'destroy']);
    Route::post('/create', [CategoryController::class, 'create']);
    Route::put('/update/{category}', [CategoryController::class, 'update']);
});

