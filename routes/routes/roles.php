<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;


Route::prefix('roles')->group(function () {
    Route::get('', [RoleController::class, 'index']);
    Route::post('/create', [RoleController::class, 'create']);
    Route::put('/update/{roleId}', [RoleController::class, 'update']);
    Route::get('{roleId}/delete/{newRoleId}', [RoleController::class, 'destroy']);
});

