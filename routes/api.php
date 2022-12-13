<?php

use App\Http\Controllers\AuthController;
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
        'message' => 'You are now on 5Million Logistics API Endpoints'
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
        Route::post('/email/verify/resend/{email}', [AuthController::class, 'email_verify_resend']);
        Route::post('/email/confirm', [AuthController::class, 'registerConfirm'])->name('email_confirmation');

        // Password reset routes
        Route::post('password/email',  [AuthController::class, 'forget_password']);
        Route::post('password/code/check', [AuthController::class, 'code_check']);
        Route::post('password/reset', [AuthController::class, 'reset_password']);
    });

    // put all api protected routes here
    Route::middleware('auth:api')->group(function () {

        Route::post('logout', [DashboardController::class, 'logout']);
    });
    
});