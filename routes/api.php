<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController , App\Http\Controllers\RoleController;

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
require base_path('routes/routes/users.php');
// Route::middleware(['middleware' => 'jwt.auth'])->group(function (){

// });


Route::group(['middleware' => 'jwt.auth'], function () {
    Route::post('refresh', [UserController::class, 'refresh']);
    Route::get('auth', [UserController::class, 'authentication']);
    require base_path('routes/routes/roles.php');
    require base_path('routes/routes/clients.php');
    require base_path('routes/routes/projects.php');
    require base_path('routes/routes/inquries.php');
    require base_path('routes/routes/invoices.php');
    require base_path('routes/routes/payments.php');
    Route::prefix('permissions')->group(function (): void {
        Route::get('', [RoleController::class, 'getAllPermissions']);
    });
});
