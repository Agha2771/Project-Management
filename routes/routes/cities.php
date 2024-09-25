<?php

use App\Http\Controllers\CityController;
use Illuminate\Support\Facades\Route;


Route::prefix('cities')->group(function () {
    Route::get('/{state?}', [CityController::class, 'index']);
    Route::get('/delete/{state}', [CityController::class, 'destroy']);
    Route::post('/create', [CityController::class, 'create']);
    Route::put('/update/{state}', [CityController::class, 'update']);
});

