<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomePageController;
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
        Route::post('/add/oversea/shipping', [DashboardController::class, 'add_oversea_shipping']);
        Route::post('/add/procurement', [DashboardController::class, 'add_procurement']);
        Route::post('/add/express/shipping', [DashboardController::class, 'add_express_shipping']);
        Route::post('/add/warehousing', [DashboardController::class, 'add_warehousing']);

        Route::post('/update/pickup/service/{id}', [DashboardController::class, 'update_pickup_service']);
        Route::post('/update/inter-state/service/{id}', [DashboardController::class, 'update_inter_state_service']);
        Route::post('/update/oversea/shipping/{id}', [DashboardController::class, 'update_oversea_shipping']);
        Route::post('/update/procurement/{id}', [DashboardController::class, 'update_procurement']);
        Route::post('/update/express/shipping/{id}', [DashboardController::class, 'update_express_shipping']);
        Route::post('/update/warehousing/{id}', [DashboardController::class, 'update_warehousing']);

        Route::post('/cancel/order/{id}', [DashboardController::class, 'cancel_order']);

        Route::get('/count/pickup/service', [DashboardController::class, 'count_pickup_service']);
        Route::get('/count/inter-state/service', [DashboardController::class, 'count_inter_state_service']);
        Route::get('/count/oversea/shipping', [DashboardController::class, 'count_oversea_shipping']);
        Route::get('/count/procurement', [DashboardController::class, 'count_procurement']);
        Route::get('/count/express/shipping', [DashboardController::class, 'count_express_shipping']);
        Route::get('/count/warehousing', [DashboardController::class, 'count_warehousing']);

        Route::get('/get/pickup/service', [DashboardController::class, 'get_pickup_service']);
        Route::get('/get/inter-state/service', [DashboardController::class, 'get_inter_state_service']);
        Route::get('/get/oversea/shipping', [DashboardController::class, 'get_oversea_shipping']);
        Route::get('/get/procurement', [DashboardController::class, 'get_procurement']);
        Route::get('/get/express/shipping', [DashboardController::class, 'get_express_shipping']);
        Route::get('/get/warehousing', [DashboardController::class, 'get_warehousing']);

        Route::get('/get/orders/by/order_id/{order_id}', [DashboardController::class, 'get_orders_by_order_id']);
        Route::get('/track/orders/{tracking_id}', [DashboardController::class, 'track_orders']);
        Route::get('/get/order-board', [DashboardController::class, 'get_order_board']);

        Route::post('logout', [DashboardController::class, 'logout']);

        // Administrator
        Route::middleware(['auth', 'isAdmin'])->group(function () {
            Route::get('/admin/get/all/user/customer', [AdminController::class, 'get_all_user_customer']);
            Route::get('/admin/get/all/user/partner', [AdminController::class, 'get_all_user_partner']);
            Route::get('/admin/get/user/{id}', [AdminController::class, 'get_user']);
            Route::post('/admin/user/profile/{id}', [AdminController::class, 'update_user_profile']);
            Route::post('/admin/user/password', [AdminController::class, 'update_user_password']);
            Route::get('/admin/user/deactivate/{id}', [AdminController::class, 'user_deactivate']);
            Route::get('/admin/user/activate/{id}', [AdminController::class, 'user_activate']);

            // Service
            Route::get('/admin/get/all/pickup/service', [AdminController::class, 'get_all_pickup_service']);
            Route::get('/admin/get/all/inter-state/service', [AdminController::class, 'get_all_inter_state_service']);
            Route::get('/admin/get/all/oversea/shipping', [AdminController::class, 'get_all_oversea_shipping']);
            Route::get('/admin/get/all/procurement', [AdminController::class, 'get_all_procurement']);
            Route::get('/admin/get/all/express/shipping', [AdminController::class, 'get_all_express_shipping']);
            Route::get('/admin/get/all/warehousing', [AdminController::class, 'get_all_warehousing']);

            // Update Service
            Route::post('/admin/update/order/{order_id}', [AdminController::class, 'update_order']);

            // Dispatch Orders
            Route::post('/admin/dispatch/order/{order_id}', [AdminController::class, 'dispatch_order']);
        });
    });

    // General Tracking
    Route::get('/general/track/orders/{tracking_id}', [HomePageController::class, 'track_order']);
});