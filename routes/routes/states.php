<?php

use App\Http\Controllers\StateController;
use Illuminate\Support\Facades\Route;


Route::prefix('states')->group(function () {
    Route::get('/{state?}', [StateController::class, 'index']);
    Route::get('/delete/{state}', [StateController::class, 'destroy']);
    Route::post('/create', [StateController::class, 'create']);
    Route::put('/update/{state}', [StateController::class, 'update']);
});

