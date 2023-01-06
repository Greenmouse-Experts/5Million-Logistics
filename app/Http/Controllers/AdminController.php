<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExpressShippingResource;
use App\Http\Resources\InterStateResource;
use App\Http\Resources\OverseaShippingResource;
use App\Http\Resources\PickupResource;
use App\Http\Resources\ProcurementResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\WarehousingResource;
use App\Models\ExpressShipping;
use App\Models\InterStateService;
use App\Models\OverseaShipping;
use App\Models\PickupService;
use App\Models\Procurement;
use App\Models\User;
use App\Models\Warehousing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function get_all_user_customer()
    {
        $customer = User::latest()->where('account_type', 'Customer')->get();

        return response()->json([
            'success' => true,
            'message' => 'All Customer User',
            'data' => UserResource::collection($customer)
        ]);
    }

    public function get_all_user_partner()
    {
        $partner = User::latest()->where('account_type', 'Partner')->get();

        return response()->json([
            'success' => true,
            'message' => 'All Partner User',
            'data' => UserResource::collection($partner)
        ]);
    }

    public function get_all_pickup_service()
    {
        $userpickupService = PickupService::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'All Pickup Service Request Retrieved Successfully',
            'data' => PickupResource::collection($userpickupService)
        ]);
    }

    public function get_all_inter_state_service()
    {
        $userinterstateService = InterStateService::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'All Inter State Service Request Retrieved Successfully',
            'data' => InterStateResource::collection($userinterstateService)
        ]);
    }

    public function get_all_oversea_shipping()
    {
        $useroverseashippingService = OverseaShipping::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'All Oversea Shipping Service Request Retrieved Successfully',
            'data' => OverseaShippingResource::collection($useroverseashippingService)
        ]);
    }

    public function get_all_procurement()
    {
        $userprocurementService = Procurement::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'All Procurement Service Request Retrieved Successfully',
            'data' => ProcurementResource::collection($userprocurementService)
        ]);
    }

    public function get_all_express_shipping()
    {
        $userexpressshippingService = ExpressShipping::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'All Express Shipping Service Request Retrieved Successfully',
            'data' => ExpressShippingResource::collection($userexpressshippingService)
        ]);
    }

    public function get_all_warehousing()
    {
        $userwarehousingService = Warehousing::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'All Warehousing Request Retrieved Successfully',
            'data' => WarehousingResource::collection($userwarehousingService)
        ]);
    }
}
