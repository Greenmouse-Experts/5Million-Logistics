<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
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

Route::get('/', function () {
    return response([
        'status' => true,
        'message' => 'You are now on 5M Logistics API Endpoints'
    ]);
});

Route::get('/login', function () {
    return response([
        'status' => true,
        'message' => 'Token Required!'
    ]);
})->name('login');

Route::group(['middleware' => ['cors', 'json.response']], function () {
    Route::prefix('/auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/admin/login', [AuthController::class, 'admin_login']);
        Route::post('/email/verify/resend/{email}', [AuthController::class, 'email_verify_resend']);
        Route::post('/email/confirm', [AuthController::class, 'registerConfirm'])->name('email_confirmation');

        // Password reset routes
        Route::post('password/email',  [AuthController::class, 'forget_password']);
        Route::post('password/code/check', [AuthController::class, 'code_check']);
        Route::post('password/reset', [AuthController::class, 'reset_password']);
    });

    // put all api protected routes here
    Route::middleware('auth:api')->group(function () {
        Route::post('/profile/update', [DashboardController::class, 'update_profile']);
        Route::post('/profile/update/password', [DashboardController::class, 'update_password']);
        Route::post('/profile/upload/profile-picture', [DashboardController::class, 'upload_profile_picture']);

        Route::post('/add/pickup/service', [DashboardController::class, 'add_pickup_service']);
        Route::post('/add/inter-state/service', [DashboardController::class, 'add_inter_state_service']);
        Route::post('/add/freight', [DashboardController::class, 'add_freight']);
        Route::post('/add/procurement', [DashboardController::class, 'add_procurement']);
        Route::post('/add/express/shipping', [DashboardController::class, 'add_express_shipping']);
        Route::post('/add/warehousing', [DashboardController::class, 'add_warehousing']);

        Route::post('logout', [DashboardController::class, 'logout']);

        // Administrator
        Route::middleware(['auth', 'isAdmin'])->group(function () {
            Route::post('/admin/add/service', [AdminController::class, 'add_service']);
            Route::post('/admin/update/service/{id}', [AdminController::class, 'update_service']);
            Route::delete('/admin/delete/service/{id}', [AdminController::class, 'delete_service']);
            Route::get('/admin/get/services', [AdminController::class, 'services']);
        });
    });
    
});