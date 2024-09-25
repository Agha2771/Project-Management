<?php

use App\Http\Controllers\SubCategoryController;
use Illuminate\Support\Facades\Route;


Route::prefix('subcategories')->group(function () {
    Route::get('/{category?}', [SubCategoryController::class, 'index']);
    Route::get('/delete/{category}', [SubCategoryController::class, 'destroy']);
    Route::post('/create', [SubCategoryController::class, 'create']);
    Route::put('/update/{category}', [SubCategoryController::class, 'update']);
});

