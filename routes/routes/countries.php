<?php

use App\Http\Controllers\CountryController;
use Illuminate\Support\Facades\Route;


Route::prefix('countries')->group(function () {
    Route::get('', [CountryController::class, 'index']);
    Route::get('/delete/{country}', [CountryController::class, 'destroy']);
    Route::post('/create', [CountryController::class, 'create']);
    Route::put('/update/{country}', [CountryController::class, 'update']);
});

