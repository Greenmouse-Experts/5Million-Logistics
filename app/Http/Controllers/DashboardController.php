<?php

namespace App\Http\Controllers;

use App\Models\ExpressShipping;
use App\Models\InterStateService;
use App\Models\OverseaShipping;
use App\Models\PickupService;
use App\Models\Procurement;
use App\Models\User;
use App\Models\Warehousing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth','verified']);
    }

    /**
     * Logout user.
     *
     * @return json
     */
    public function logout()
    {
        $access_token = auth()->user()->token();

        // logout from only current device
        $tokenRepository = app(TokenRepository::class);
        $tokenRepository->revokeAccessToken($access_token->id);

        // use this method to logout from all devices
        $refreshTokenRepository = app(RefreshTokenRepository::class);
        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($access_token->id);

        return response()->json([
            'success' => true,
            'message' => 'User logout successfully.'
        ], 200);
    }

    function tracking_number_generate($input, $strength = 10) 
    {
        $input = '01234567890123456789';
        $input_length = strlen($input);
        $random_string = '';
        for($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
    
        return $random_string;
    }

    public function add_pickup_service(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'pickup_vehicle' => 'required|string|max:244|min:1',
            'pickup_address' => 'required|string|max:244|min:1',
            'dropoff_address' => 'required|string|max:244|min:1',
            'sender_address' => 'required|string|max:244|min:1',
            'sender_name' => 'required|string|max:244|min:1',
            'sender_phone_number' => 'required|string|max:244|min:1',
            'receiver_address' => 'required|string|max:244|min:1',
            'receiver_name' => 'required|string|max:244|min:1',
            'receiver_phone_number' => 'required|string|max:244|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ]);
        }

        $pickupService = PickupService::create([
            'user_id' => Auth::user()->id,
            // 'tracking_number' => 'PUS-'.$this->tracking_number_generate(10),
            'order_id' => 'PUS-ORD-'.$this->tracking_number_generate(10),
            'pickup_vehicle' => $request->pickup_vehicle,
            'pickup_address' => $request->pickup_address,
            'dropoff_address' => $request->dropoff_address,
            'sender_address' => $request->sender_address,
            'sender_name' => $request->sender_name,
            'sender_phone_number' => $request->sender_phone_number,
            'receiver_address' => $request->receiver_address,
            'receiver_name' => $request->receiver_name,
            'receiver_phone_number' => $request->receiver_phone_number,
        ]);
 
        return response()->json([
            'success' => true,
            'message' => 'Request sent successfully, kindly wait while the Administrator reviews your request. Thank you.',
            'data' => $pickupService
        ]);
    }

    public function add_inter_state_service(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'package_address' => 'required|string|max:244|min:1',
            'dropoff_address' => 'required|string|max:244|min:1',
            'sender_address' => 'required|string|max:244|min:1',
            'sender_name' => 'required|string|max:244|min:1',
            'sender_phone_number' => 'required|string|max:244|min:1',
            'receiver_address' => 'required|string|max:244|min:1',
            'receiver_name' => 'required|string|max:244|min:1',
            'receiver_phone_number' => 'required|string|max:244|min:1',
            'dimension' => 'required|string|max:244|min:1',
            'weight' => 'required|string|max:244|min:1',
            'value' => 'required|string|max:244|min:1',
            'description' => 'required|string|max:244|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ]);
        }

        $interStateService = InterStateService::create([
            'user_id' => Auth::user()->id,
            // 'tracking_number' => 'ISS-'.$this->tracking_number_generate(10),
            'order_id' => 'ISS-ORD-'.$this->tracking_number_generate(10),
            'package_address' => $request->package_address,
            'dropoff_address' => $request->dropoff_address,
            'sender_address' => $request->sender_address,
            'sender_name' => $request->sender_name,
            'sender_phone_number' => $request->sender_phone_number,
            'receiver_address' => $request->receiver_address,
            'receiver_name' => $request->receiver_name,
            'receiver_phone_number' => $request->receiver_phone_number,
            'dimension' => $request->dimension,
            'weight' => $request->weight,
            'value' => $request->value,
            'description' => $request->description,
        ]);
 
        return response()->json([
            'success' => true,
            'message' => 'Request sent successfully, kindly wait while the Administrator reviews your request. Thank you.',
            'data' => $interStateService
        ]);
    }
    
    public function add_oversea_shipping(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'freight_service' => 'required|string|max:244|min:1',
            'owner_full_name' => 'required|string|max:244|min:1',
            'owner_address' => 'required|string|max:244|min:1',
            'owner_email' => 'required|string|email|max:244|min:1',
            'owner_phone_number' => 'required|string|max:244|min:1',
            'date_of_shipment' => 'required|date',
            'shipping_from_street_address' => 'required|string|max:244|min:1',
            'shipping_from_city' => 'required|string|max:244|min:1',
            'shipping_from_state_province_region' => 'required|string|max:244|min:1',
            'shipping_from_zip_portal_code' => 'required|string|max:244|min:1',
            'shipping_from_country' => 'required|string|max:244|min:1',
            'shipping_to_street_address' => 'required|string|max:244|min:1',
            'shipping_to_city' => 'required|string|max:244|min:1',
            'shipping_to_state_province_region' => 'required|string|max:244|min:1',
            'shipping_to_zip_portal_code' => 'required|string|max:244|min:1',
            'shipping_to_country' => 'required|string|max:244|min:1',
            'package_name' => 'required|string|max:244|min:1',
            'package_quantity' => 'required|string|max:244|min:1',
            'package_dimension' => 'required|string|max:244|min:1',
            'package_weight' => 'required|string|max:244|min:1',
            'package_value' => 'required|string|max:244|min:1',
            'package_description' => 'required|string|max:244|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ]);
        }

        $overseashipping = OverseaShipping::create([
            'user_id' => Auth::user()->id,
            // 'tracking_number' => 'OSS-'.$this->tracking_number_generate(10),
            'order_id' => 'OSS-ORD-'.$this->tracking_number_generate(10),
            'freight_service' => $request->freight_service,
            'owner_full_name' => $request->owner_full_name,
            'owner_address' => $request->owner_address,
            'owner_email' => $request->owner_email,
            'owner_phone_number' => $request->owner_phone_number,
            'date_of_shipment' => $request->date_of_shipment,
            'shipping_from_street_address' => $request->shipping_from_street_address,
            'shipping_from_city' => $request->shipping_from_city,
            'shipping_from_state_province_region' => $request->shipping_from_state_province_region,
            'shipping_from_zip_portal_code' => $request->shipping_from_zip_portal_code,
            'shipping_from_country' => $request->shipping_from_country,
            'shipping_to_street_address' => $request->shipping_to_street_address,
            'shipping_to_city' => $request->shipping_to_city,
            'shipping_to_state_province_region' => $request->shipping_to_state_province_region,
            'shipping_to_zip_portal_code' => $request->shipping_to_zip_portal_code,
            'shipping_to_country' => $request->shipping_to_country,
            'package_name' => $request->package_name,
            'package_quantity' => $request->package_quantity,
            'package_dimension' => $request->package_dimension,
            'package_weight' => $request->package_weight,
            'package_value' => $request->package_value,
            'package_description' => $request->package_description,
        ]);
 
        return response()->json([
            'success' => true,
            'message' => 'Request sent successfully, kindly wait while the Administrator reviews your request. Thank you.',
            'data' => $overseashipping
        ]);
    }

    public function add_procurement(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'item_name' => 'required|string|max:244|min:1',
            'item_type' => 'required|string|max:244|min:1',
            'item_store_name' => 'required|string|max:244|min:1',
            'item_description' => 'required|string|max:244|min:1',
            'item_tracking_id' => 'max:244',
            'item_value' => 'required|string|max:244|min:1',
            'owner_full_name' => 'required|string|max:244|min:1',
            'owner_address' => 'required|string|max:244|min:1',
            'owner_email' => 'required|string|email|max:244|min:1',
            'owner_phone_number' => 'required|string|max:244|min:1',
            'date_of_shipment' => 'required|date',
            'shipping_from_street_address' => 'required|string|max:244|min:1',
            'shipping_from_city' => 'required|string|max:244|min:1',
            'shipping_from_state_province_region' => 'required|string|max:244|min:1',
            'shipping_from_zip_portal_code' => 'required|string|max:244|min:1',
            'shipping_from_country' => 'required|string|max:244|min:1',
            'shipping_to_street_address' => 'required|string|max:244|min:1',
            'shipping_to_city' => 'required|string|max:244|min:1',
            'shipping_to_state_province_region' => 'required|string|max:244|min:1',
            'shipping_to_zip_portal_code' => 'required|string|max:244|min:1',
            'shipping_to_country' => 'required|string|max:244|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ]);
        }

        $procurement = Procurement::create([
            'user_id' => Auth::user()->id,
            // 'tracking_number' => 'PCM-'.$this->tracking_number_generate(10),
            'order_id' => 'PCM-ORD-'.$this->tracking_number_generate(10),
            'item_name' => $request->item_name,
            'item_type' => $request->item_type,
            'item_store_name' => $request->item_store_name,
            'item_description' => $request->item_description,
            'item_tracking_id' => $request->item_tracking_id,
            'item_value' => $request->item_value,
            'owner_full_name' => $request->owner_full_name,
            'owner_address' => $request->owner_address,
            'owner_email' => $request->owner_email,
            'owner_phone_number' => $request->owner_phone_number,
            'date_of_shipment' => $request->date_of_shipment,
            'shipping_from_street_address' => $request->shipping_from_street_address,
            'shipping_from_city' => $request->shipping_from_city,
            'shipping_from_state_province_region' => $request->shipping_from_state_province_region,
            'shipping_from_zip_portal_code' => $request->shipping_from_zip_portal_code,
            'shipping_from_country' => $request->shipping_from_country,
            'shipping_to_street_address' => $request->shipping_to_street_address,
            'shipping_to_city' => $request->shipping_to_city,
            'shipping_to_state_province_region' => $request->shipping_to_state_province_region,
            'shipping_to_zip_portal_code' => $request->shipping_to_zip_portal_code,
            'shipping_to_country' => $request->shipping_to_country,
        ]);
 
        return response()->json([
            'success' => true,
            'message' => 'Request sent successfully, kindly wait while the Administrator reviews your request. Thank you.',
            'data' => $procurement
        ]);
    }

    public function add_express_shipping(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'freight_service' => 'required|string|max:244|min:1',
            'owner_full_name' => 'required|string|max:244|min:1',
            'owner_address' => 'required|string|max:244|min:1',
            'owner_email' => 'required|string|email|max:244|min:1',
            'owner_phone_number' => 'required|string|max:244|min:1',
            'date_of_shipment' => 'required|date',
            'shipping_from_street_address' => 'required|string|max:244|min:1',
            'shipping_from_city' => 'required|string|max:244|min:1',
            'shipping_from_state_province_region' => 'required|string|max:244|min:1',
            'shipping_from_zip_portal_code' => 'required|string|max:244|min:1',
            'shipping_from_country' => 'required|string|max:244|min:1',
            'shipping_to_street_address' => 'required|string|max:244|min:1',
            'shipping_to_city' => 'required|string|max:244|min:1',
            'shipping_to_state_province_region' => 'required|string|max:244|min:1',
            'shipping_to_zip_portal_code' => 'required|string|max:244|min:1',
            'shipping_to_country' => 'required|string|max:244|min:1',
            'package_name' => 'required|string|max:244|min:1',
            'package_quantity' => 'required|numeric',
            'package_dimension' => 'required|string|max:244|min:1',
            'package_weight' => 'required|string|max:244|min:1',
            'package_value' => 'required|string|max:244|min:1',
            'package_description' => 'required|string|max:244|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ]);
        }

        $expressShipping = ExpressShipping::create([
            'user_id' => Auth::user()->id,
            // 'tracking_number' => 'EXS-'.$this->tracking_number_generate(10),
            'order_id' => 'EXS-ORD-'.$this->tracking_number_generate(10),
            'freight_service' => $request->freight_service,
            'owner_full_name' => $request->owner_full_name,
            'owner_address' => $request->owner_address,
            'owner_email' => $request->owner_email,
            'owner_phone_number' => $request->owner_phone_number,
            'date_of_shipment' => $request->date_of_shipment,
            'shipping_from_street_address' => $request->shipping_from_street_address,
            'shipping_from_city' => $request->shipping_from_city,
            'shipping_from_state_province_region' => $request->shipping_from_state_province_region,
            'shipping_from_zip_portal_code' => $request->shipping_from_zip_portal_code,
            'shipping_from_country' => $request->shipping_from_country,
            'shipping_to_street_address' => $request->shipping_to_street_address,
            'shipping_to_city' => $request->shipping_to_city,
            'shipping_to_state_province_region' => $request->shipping_to_state_province_region,
            'shipping_to_zip_portal_code' => $request->shipping_to_zip_portal_code,
            'shipping_to_country' => $request->shipping_to_country,
            'package_name' => $request->package_name,
            'package_quantity' => $request->package_quantity,
            'package_dimension' => $request->package_dimension,
            'package_weight' => $request->package_weight,
            'package_value' => $request->package_value,
            'package_description' => $request->package_description,
        ]);
 
        return response()->json([
            'success' => true,
            'message' => 'Request sent successfully, kindly wait while the Administrator reviews your request. Thank you.',
            'data' => $expressShipping
        ]);
    }

    public function add_warehousing(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'warehouse_location' => 'required|string|max:244|min:1',
            'package_name' => 'required|string|max:244|min:1',
            'package_quantity' => 'required|numeric',
            'package_dimension' => 'required|string|max:244|min:1',
            'package_weight' => 'required|string|max:244|min:1',
            'package_value' => 'required|string|max:244|min:1',
            'package_description' => 'required|string|max:244|min:1',
            'storage_start_date' => 'required|date',
            'storage_end_date' => 'required|date|after:storage_start_date',
            'owner_full_name' => 'required|string|max:244|min:1',
            'owner_address' => 'required|string|max:244|min:1',
            'owner_phone_number' => 'required|string|max:244|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ]);
        }

        $warehousing = Warehousing::create([
            'user_id' => Auth::user()->id,
            // 'tracking_number' => 'WAH-'.$this->tracking_number_generate(10),
            'order_id' => 'WAH-ORD-'.$this->tracking_number_generate(10),
            'warehouse_location' => $request->warehouse_location,
            'package_name' => $request->package_name,
            'package_quantity' => $request->package_quantity,
            'package_dimension' => $request->package_dimension,
            'package_weight' => $request->package_weight,
            'package_value' => $request->package_value,
            'package_description' => $request->package_description,
            'storage_start_date' => $request->storage_start_date,
            'storage_end_date' => $request->storage_end_date,
            'owner_full_name' => $request->owner_full_name,
            'owner_address' => $request->owner_address,
            'owner_phone_number' => $request->owner_phone_number,
        ]);
 
        return response()->json([
            'success' => true,
            'message' => 'Request sent successfully, kindly wait while the Administrator reviews your request. Thank you.',
            'data' => $warehousing
        ]);
    }

    public function update_pickup_service($id, Request $request)
    {
        $pickupService = PickupService::findorfail($id);

        if($pickupService->status == 'Pending')
        {
            $validator = Validator::make(request()->all(), [
                'pickup_vehicle' => 'required|string|max:244|min:1',
                'pickup_address' => 'required|string|max:244|min:1',
                'dropoff_address' => 'required|string|max:244|min:1',
                'sender_address' => 'required|string|max:244|min:1',
                'sender_name' => 'required|string|max:244|min:1',
                'sender_phone_number' => 'required|string|max:244|min:1',
                'receiver_address' => 'required|string|max:244|min:1',
                'receiver_name' => 'required|string|max:244|min:1',
                'receiver_phone_number' => 'required|string|max:244|min:1'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please see errors parameter for all errors.',
                    'errors' => $validator->errors()
                ]);
            }

            $pickupService->update([
                'pickup_vehicle' => $request->pickup_vehicle,
                'pickup_address' => $request->pickup_address,
                'dropoff_address' => $request->dropoff_address,
                'sender_address' => $request->sender_address,
                'sender_name' => $request->sender_name,
                'sender_phone_number' => $request->sender_phone_number,
                'receiver_address' => $request->receiver_address,
                'receiver_name' => $request->receiver_name,
                'receiver_phone_number' => $request->receiver_phone_number,
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Request Updated Successfully.',
                'data' => $pickupService
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Request not completed, it accepts only when the request sent is pending.',
        ]);
    }

    public function update_inter_state_service($id, Request $request)
    {
        $interStateService = InterStateService::findorfail($id);

        if($interStateService->status == 'Pending')
        {
            $validator = Validator::make(request()->all(), [
                'package_address' => 'required|string|max:244|min:1',
                'dropoff_address' => 'required|string|max:244|min:1',
                'sender_address' => 'required|string|max:244|min:1',
                'sender_name' => 'required|string|max:244|min:1',
                'sender_phone_number' => 'required|string|max:244|min:1',
                'receiver_address' => 'required|string|max:244|min:1',
                'receiver_name' => 'required|string|max:244|min:1',
                'receiver_phone_number' => 'required|string|max:244|min:1',
                'dimension' => 'required|string|max:244|min:1',
                'weight' => 'required|string|max:244|min:1',
                'value' => 'required|string|max:244|min:1',
                'description' => 'required|string|max:244|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please see errors parameter for all errors.',
                    'errors' => $validator->errors()
                ]);
            }

            $interStateService->update([
                'package_address' => $request->package_address,
                'dropoff_address' => $request->dropoff_address,
                'sender_address' => $request->sender_address,
                'sender_name' => $request->sender_name,
                'sender_phone_number' => $request->sender_phone_number,
                'receiver_address' => $request->receiver_address,
                'receiver_name' => $request->receiver_name,
                'receiver_phone_number' => $request->receiver_phone_number,
                'dimension' => $request->dimension,
                'weight' => $request->weight,
                'value' => $request->value,
                'description' => $request->description,
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Request Updated Successfully.',
                'data' => $interStateService
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Request not completed, it accepts only when the request sent is pending.',
        ]);
    }
    
    public function update_oversea_shipping($id, Request $request)
    {
        $overseashipping = OverseaShipping::findorfail($id);

        if($overseashipping->status == 'Pending')
        {
            $validator = Validator::make(request()->all(), [
                'freight_service' => 'required|string|max:244|min:1',
                'owner_full_name' => 'required|string|max:244|min:1',
                'owner_address' => 'required|string|max:244|min:1',
                'owner_email' => 'required|string|email|max:244|min:1',
                'owner_phone_number' => 'required|string|max:244|min:1',
                'date_of_shipment' => 'required|date',
                'shipping_from_street_address' => 'required|string|max:244|min:1',
                'shipping_from_city' => 'required|string|max:244|min:1',
                'shipping_from_state_province_region' => 'required|string|max:244|min:1',
                'shipping_from_zip_portal_code' => 'required|string|max:244|min:1',
                'shipping_from_country' => 'required|string|max:244|min:1',
                'shipping_to_street_address' => 'required|string|max:244|min:1',
                'shipping_to_city' => 'required|string|max:244|min:1',
                'shipping_to_state_province_region' => 'required|string|max:244|min:1',
                'shipping_to_zip_portal_code' => 'required|string|max:244|min:1',
                'shipping_to_country' => 'required|string|max:244|min:1',
                'package_name' => 'required|string|max:244|min:1',
                'package_quantity' => 'required|string|max:244|min:1',
                'package_dimension' => 'required|string|max:244|min:1',
                'package_weight' => 'required|string|max:244|min:1',
                'package_value' => 'required|string|max:244|min:1',
                'package_description' => 'required|string|max:244|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please see errors parameter for all errors.',
                    'errors' => $validator->errors()
                ]);
            }

            $overseashipping->update([
                'freight_service' => $request->freight_service,
                'owner_full_name' => $request->owner_full_name,
                'owner_address' => $request->owner_address,
                'owner_email' => $request->owner_email,
                'owner_phone_number' => $request->owner_phone_number,
                'date_of_shipment' => $request->date_of_shipment,
                'shipping_from_street_address' => $request->shipping_from_street_address,
                'shipping_from_city' => $request->shipping_from_city,
                'shipping_from_state_province_region' => $request->shipping_from_state_province_region,
                'shipping_from_zip_portal_code' => $request->shipping_from_zip_portal_code,
                'shipping_from_country' => $request->shipping_from_country,
                'shipping_to_street_address' => $request->shipping_to_street_address,
                'shipping_to_city' => $request->shipping_to_city,
                'shipping_to_state_province_region' => $request->shipping_to_state_province_region,
                'shipping_to_zip_portal_code' => $request->shipping_to_zip_portal_code,
                'shipping_to_country' => $request->shipping_to_country,
                'package_name' => $request->package_name,
                'package_quantity' => $request->package_quantity,
                'package_dimension' => $request->package_dimension,
                'package_weight' => $request->package_weight,
                'package_value' => $request->package_value,
                'package_description' => $request->package_description,
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Request Updated Successfully.',
                'data' => $overseashipping
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Request not completed, it accepts only when the request sent is pending.',
        ]);
    }

    public function update_procurement($id, Request $request)
    {
        $procurement = Procurement::findorfail($id);

        if($procurement->status == 'Pending')
        {
            $validator = Validator::make(request()->all(), [
                'item_name' => 'required|string|max:244|min:1',
                'item_type' => 'required|string|max:244|min:1',
                'item_store_name' => 'required|string|max:244|min:1',
                'item_description' => 'required|string|max:244|min:1',
                'item_tracking_id' => 'max:244',
                'item_value' => 'required|string|max:244|min:1',
                'owner_full_name' => 'required|string|max:244|min:1',
                'owner_address' => 'required|string|max:244|min:1',
                'owner_email' => 'required|string|email|max:244|min:1',
                'owner_phone_number' => 'required|string|max:244|min:1',
                'date_of_shipment' => 'required|date',
                'shipping_from_street_address' => 'required|string|max:244|min:1',
                'shipping_from_city' => 'required|string|max:244|min:1',
                'shipping_from_state_province_region' => 'required|string|max:244|min:1',
                'shipping_from_zip_portal_code' => 'required|string|max:244|min:1',
                'shipping_from_country' => 'required|string|max:244|min:1',
                'shipping_to_street_address' => 'required|string|max:244|min:1',
                'shipping_to_city' => 'required|string|max:244|min:1',
                'shipping_to_state_province_region' => 'required|string|max:244|min:1',
                'shipping_to_zip_portal_code' => 'required|string|max:244|min:1',
                'shipping_to_country' => 'required|string|max:244|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please see errors parameter for all errors.',
                    'errors' => $validator->errors()
                ]);
            }

            $procurement->update([
                'item_name' => $request->item_name,
                'item_type' => $request->item_type,
                'item_store_name' => $request->item_store_name,
                'item_description' => $request->item_description,
                'item_tracking_id' => $request->item_tracking_id,
                'item_value' => $request->item_value,
                'owner_full_name' => $request->owner_full_name,
                'owner_address' => $request->owner_address,
                'owner_email' => $request->owner_email,
                'owner_phone_number' => $request->owner_phone_number,
                'date_of_shipment' => $request->date_of_shipment,
                'shipping_from_street_address' => $request->shipping_from_street_address,
                'shipping_from_city' => $request->shipping_from_city,
                'shipping_from_state_province_region' => $request->shipping_from_state_province_region,
                'shipping_from_zip_portal_code' => $request->shipping_from_zip_portal_code,
                'shipping_from_country' => $request->shipping_from_country,
                'shipping_to_street_address' => $request->shipping_to_street_address,
                'shipping_to_city' => $request->shipping_to_city,
                'shipping_to_state_province_region' => $request->shipping_to_state_province_region,
                'shipping_to_zip_portal_code' => $request->shipping_to_zip_portal_code,
                'shipping_to_country' => $request->shipping_to_country,
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Request Updated Successfully.',
                'data' => $procurement
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Request not completed, it accepts only when the request sent is pending.',
        ]);
    }

    public function update_express_shipping($id, Request $request)
    {
        $expressShipping = Procurement::findorfail($id);

        if($expressShipping->status == 'Pending')
        {
            $validator = Validator::make(request()->all(), [
                'freight_service' => 'required|string|max:244|min:1',
                'owner_full_name' => 'required|string|max:244|min:1',
                'owner_address' => 'required|string|max:244|min:1',
                'owner_email' => 'required|string|email|max:244|min:1',
                'owner_phone_number' => 'required|string|max:244|min:1',
                'date_of_shipment' => 'required|date',
                'shipping_from_street_address' => 'required|string|max:244|min:1',
                'shipping_from_city' => 'required|string|max:244|min:1',
                'shipping_from_state_province_region' => 'required|string|max:244|min:1',
                'shipping_from_zip_portal_code' => 'required|string|max:244|min:1',
                'shipping_from_country' => 'required|string|max:244|min:1',
                'shipping_to_street_address' => 'required|string|max:244|min:1',
                'shipping_to_city' => 'required|string|max:244|min:1',
                'shipping_to_state_province_region' => 'required|string|max:244|min:1',
                'shipping_to_zip_portal_code' => 'required|string|max:244|min:1',
                'shipping_to_country' => 'required|string|max:244|min:1',
                'package_name' => 'required|string|max:244|min:1',
                'package_quantity' => 'required|numeric',
                'package_dimension' => 'required|string|max:244|min:1',
                'package_weight' => 'required|string|max:244|min:1',
                'package_value' => 'required|string|max:244|min:1',
                'package_description' => 'required|string|max:244|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please see errors parameter for all errors.',
                    'errors' => $validator->errors()
                ]);
            }

            $expressShipping->update([
                'user_id' => Auth::user()->id,
                'tracking_number' => $this->tracking_number_generate(10),
                'freight_service' => $request->freight_service,
                'owner_full_name' => $request->owner_full_name,
                'owner_address' => $request->owner_address,
                'owner_email' => $request->owner_email,
                'owner_phone_number' => $request->owner_phone_number,
                'date_of_shipment' => $request->date_of_shipment,
                'shipping_from_street_address' => $request->shipping_from_street_address,
                'shipping_from_city' => $request->shipping_from_city,
                'shipping_from_state_province_region' => $request->shipping_from_state_province_region,
                'shipping_from_zip_portal_code' => $request->shipping_from_zip_portal_code,
                'shipping_from_country' => $request->shipping_from_country,
                'shipping_to_street_address' => $request->shipping_to_street_address,
                'shipping_to_city' => $request->shipping_to_city,
                'shipping_to_state_province_region' => $request->shipping_to_state_province_region,
                'shipping_to_zip_portal_code' => $request->shipping_to_zip_portal_code,
                'shipping_to_country' => $request->shipping_to_country,
                'package_name' => $request->package_name,
                'package_quantity' => $request->package_quantity,
                'package_dimension' => $request->package_dimension,
                'package_weight' => $request->package_weight,
                'package_value' => $request->package_value,
                'package_description' => $request->package_description,
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Request Updated Successfully.',
                'data' => $expressShipping
            ]);
        } 

        return response()->json([
            'success' => false,
            'message' => 'Request not completed, it accepts only when the request sent is pending.',
        ]);
    }

    public function update_warehousing($id, Request $request)
    {
        $warehousing = Warehousing::findorfail($id);

        if($warehousing->status == 'Pending')
        {
            $validator = Validator::make(request()->all(), [
                'warehouse_location' => 'required|string|max:244|min:1',
                'package_name' => 'required|string|max:244|min:1',
                'package_quantity' => 'required|numeric',
                'package_dimension' => 'required|string|max:244|min:1',
                'package_weight' => 'required|string|max:244|min:1',
                'package_value' => 'required|string|max:244|min:1',
                'package_description' => 'required|string|max:244|min:1',
                'storage_start_date' => 'required|date',
                'storage_end_date' => 'required|date|after:storage_start_date',
                'owner_full_name' => 'required|string|max:244|min:1',
                'owner_address' => 'required|string|max:244|min:1',
                'owner_phone_number' => 'required|string|max:244|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please see errors parameter for all errors.',
                    'errors' => $validator->errors()
                ]);
            }

            $warehousing->update([
                'warehouse_location' => $request->warehouse_location,
                'package_name' => $request->package_name,
                'package_quantity' => $request->package_quantity,
                'package_dimension' => $request->package_dimension,
                'package_weight' => $request->package_weight,
                'package_value' => $request->package_value,
                'package_description' => $request->package_description,
                'storage_start_date' => $request->storage_start_date,
                'storage_end_date' => $request->storage_end_date,
                'owner_full_name' => $request->owner_full_name,
                'owner_address' => $request->owner_address,
                'owner_phone_number' => $request->owner_phone_number,
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Request Updated Successfully.',
                'data' => $warehousing
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Request not completed, it accepts only when the request sent is pending.',
        ]);
    }

    public function cancel_order($order_id)
    {
        $result = substr($order_id, 0, 7);

        if(strtoupper($result) == 'PUS-ORD')
        {
            $pickupService = PickupService::where('order_id',$order_id)->first();

            if($pickupService)
            {
                if($pickupService->status == 'New')
                {
                    $pickupService->delete();
            
                    return response()->json([
                        'success' => true,
                        'message' => 'Request Cancelled.'
                    ]);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Request not completed, it accepts only when the request sent is pending.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Not Found.',
            ]);
        }

        if(strtoupper($result) == 'ISS-ORD')
        {
            $interStateService = InterStateService::where('order_id', $order_id)->first();

            if($interStateService)
            {
                if($interStateService->status == 'New')
                {
                    $interStateService->delete();
            
                    return response()->json([
                        'success' => true,
                        'message' => 'Request Cancelled.'
                    ]);
                }
                
                return response()->json([
                    'success' => false,
                    'message' => 'Request not completed, it accepts only when the request sent is pending.',
                ]);
            } 

            return response()->json([
                'success' => false,
                'message' => 'Not Found.',
            ]);
        }

        if(strtoupper($result) == 'OSS-ORD')
        {
            $overseashipping = OverseaShipping::where('order_id', $order_id)->first();

            if($overseashipping)
            {
                if($overseashipping->status == 'New')
                {
                    $overseashipping->delete();
            
                    return response()->json([
                        'success' => true,
                        'message' => 'Request Cancelled.'
                    ]);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Request not completed, it accepts only when the request sent is pending.',
                ]);

            } 

            return response()->json([
                'success' => false,
                'message' => 'Not Found.',
            ]);
        }

        if(strtoupper($result) == 'PCM-ORD')
        {
            $procurement = Procurement::where('order_id', $order_id)->first();

            if($procurement)
            {
                if($procurement->status == 'New')
                {
                    $procurement->delete();
            
                    return response()->json([
                        'success' => true,
                        'message' => 'Request Cancelled'
                    ]);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Request not completed, it accepts only when the request sent is pending.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Not Found.',
            ]);
        }

        if(strtoupper($result) == 'EXS-ORD')
        {
            $expressShipping = ExpressShipping::where('order_id', $order_id)->first();

            if($expressShipping)
            {
                if($expressShipping->status == 'New')
                {
                    $expressShipping->delete();
            
                    return response()->json([
                        'success' => true,
                        'message' => 'Request Cancelled'
                    ]);
                } 
        
                return response()->json([
                    'success' => false,
                    'message' => 'Request not completed, it accepts only when the request sent is pending.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Not Found.',
            ]);
        }

        if(strtoupper($result) == 'WAH-ORD')
        {
            $warehousing = Warehousing::where('order_id', $order_id)->first();

            if($warehousing)
            {
                if($warehousing->status == 'New')
                {
                    $warehousing->delete();
            
                    return response()->json([
                        'success' => true,
                        'message' => 'Request Cancelled'
                    ]);
                }

                return response()->json([
                    'success' => false,
                    'message' => 'Request not completed, it accepts only when the request sent is pending.',
                ]);
            } 

            return response()->json([
                'success' => false,
                'message' => 'Not Found.',
            ]);
        }
           
        return response()->json([
            'success' => false,
            'message' => 'No Details found. Try again !',
        ]); 
    }

    public function update_profile(Request $request)
    {
        $input = $request->only(['first_name', 'last_name', 'phone_number']);

        $validate_data = [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:255']
        ];

        $validator = Validator::make($input, $validate_data);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ]);
        }

        $user = User::findorfail(Auth::user()->id);
        
        if($user->email == $request->email)
        {
            $user->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'gender' => $request->gender,
                'phone_number' => $request->phone_number,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country
            ]); 
        } else {
            //Validate Request
            $validator = Validator::make(request()->all(), [
                'email' => ['string', 'email', 'max:255', 'unique:users'],
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please see errors parameter for all errors.',
                    'errors' => $validator->errors()
                ]);
            }

            $user->update([
                'email' => $request->email,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'gender' => $request->gender,
                'phone_number' => $request->phone_number,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country
            ]); 
        }

        return response()->json([
            'success' => true,
            'message' => 'Profile Updated Successfully',
            'data' => $user
        ]);
    }

    public function update_password(Request $request)
    {
        $input = $request->only(['new_password', 'new_password_confirmation']);

        $validate_data = [
            'new_password' => ['required', 'string', 'min:8', 'confirmed']
        ];

        $validator = Validator::make($input, $validate_data);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ]);
        }
        
        $user = User::findorfail(Auth::user()->id);
        
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password Updated Successfully',
            'data' => $user
        ]);
    }

    public function upload_profile_picture(Request $request) 
    {
        $input = $request->only(['avatar']);

        $validate_data = [
            'avatar' => 'required|mimes:jpeg,png,jpg',
        ];

        $validator = Validator::make($input, $validate_data);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ]);
        }

        //User
        $user = User::findorfail(Auth::user()->id);

        $filename = request()->avatar->getClientOriginalName();
        if($user->photo) {
            Storage::delete(str_replace("storage", "public", $user->photo));
        }
        request()->avatar->storeAs('users_avatar', $filename, 'public');
        $user->photo = '/storage/users_avatar/'.$filename;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile Picture Uploaded Successfully!',
            'data' => $user
        ]);
    }

    public function count_pickup_service()
    {
        $userpickupService = PickupService::latest()->where('user_id', Auth::user()->id)->get()->count();

        return response()->json([
            'success' => true,
            'message' => 'Count User Pickup Service Request',
            'data' => $userpickupService
        ]);
    }

    public function count_inter_state_service()
    {
        $userinterstateService = InterStateService::latest()->where('user_id', Auth::user()->id)->get()->count();

        return response()->json([
            'success' => true,
            'message' => 'Count User Inter State Service Request',
            'data' => $userinterstateService
        ]);
    }

    public function count_oversea_shipping()
    {
        $useroverseashippingService = OverseaShipping::latest()->where('user_id', Auth::user()->id)->get()->count();

        return response()->json([
            'success' => true,
            'message' => 'Count User Oversea Shipping Service Request',
            'data' => $useroverseashippingService
        ]);
    }

    public function count_procurement()
    {
        $userprocurementService = Procurement::latest()->where('user_id', Auth::user()->id)->get()->count();

        return response()->json([
            'success' => true,
            'message' => 'Count User Procurement Service Request',
            'data' => $userprocurementService
        ]);
    }

    public function count_express_shipping()
    {
        $userexpressshippingService = ExpressShipping::latest()->where('user_id', Auth::user()->id)->get()->count();

        return response()->json([
            'success' => true,
            'message' => 'Count User Express Shipping Service Request',
            'data' => $userexpressshippingService
        ]);
    }

    public function count_warehousing()
    {
        $userwarehousingService = Warehousing::latest()->where('user_id', Auth::user()->id)->get()->count();

        return response()->json([
            'success' => true,
            'message' => 'Count User Warehousing Request',
            'data' => $userwarehousingService
        ]);
    }

    public function get_pickup_service()
    {
        $userpickupService = PickupService::latest()->where('user_id', Auth::user()->id)->get();

        return response()->json([
            'success' => true,
            'message' => 'User Pickup Service Request Retrieved Successfully',
            'data' => $userpickupService
        ]);
    }

    public function get_inter_state_service()
    {
        $userinterstateService = InterStateService::latest()->where('user_id', Auth::user()->id)->get();

        return response()->json([
            'success' => true,
            'message' => 'User Inter State Service Request Retrieved Successfully',
            'data' => $userinterstateService
        ]);
    }

    public function get_oversea_shipping()
    {
        $useroverseashippingService = OverseaShipping::latest()->where('user_id', Auth::user()->id)->get();

        return response()->json([
            'success' => true,
            'message' => 'User Oversea Shipping Service Request Retrieved Successfully',
            'data' => $useroverseashippingService
        ]);
    }

    public function get_procurement()
    {
        $userprocurementService = Procurement::latest()->where('user_id', Auth::user()->id)->get();

        return response()->json([
            'success' => true,
            'message' => 'User Procurement Service Request Retrieved Successfully',
            'data' => $userprocurementService
        ]);
    }

    public function get_express_shipping()
    {
        $userexpressshippingService = ExpressShipping::latest()->where('user_id', Auth::user()->id)->get();

        return response()->json([
            'success' => true,
            'message' => 'User Express Shipping Service Request Retrieved Successfully',
            'data' => $userexpressshippingService
        ]);
    }

    public function get_warehousing()
    {
        $userwarehousingService = Warehousing::latest()->where('user_id', Auth::user()->id)->get();

        return response()->json([
            'success' => true,
            'message' => 'User Warehousing Request Retrieved Successfully',
            'data' => $userwarehousingService
        ]);
    }

    public function track_orders($tracking_id)
    {
        $result = substr($tracking_id, 0, 3);

        if(strtoupper($result) == 'PUS')
        {
            $order = PickupService::where('tracking_number','LIKE','%'.$tracking_id.'%')->get();

            if(count($order) > 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order Retrieved Successfully',
                    'data' => $order
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No Details found. Try to search again !',
                ]); 
            }
        }

        if(strtoupper($result) == 'ISS')
        {
            $order = InterStateService::where('tracking_number','LIKE','%'.$tracking_id.'%')->get();

            if(count($order) > 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order Retrieved Successfully',
                    'data' => $order
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No Details found. Try to search again !',
                ]); 
            }
        }

        if(strtoupper($result) == 'OSS')
        {
            $order = OverseaShipping::where('tracking_number','LIKE','%'.$tracking_id.'%')->get();

            if(count($order) > 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order Retrieved Successfully',
                    'data' => $order
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No Details found. Try to search again !',
                ]); 
            }
        }

        if(strtoupper($result) == 'PCM')
        {
            $order = Procurement::where('tracking_number','LIKE','%'.$tracking_id.'%')->get();

            if(count($order) > 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order Retrieved Successfully',
                    'data' => $order
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No Details found. Try to search again !',
                ]); 
            }
        }

        if(strtoupper($result) == 'EXS')
        {
            $order = ExpressShipping::where('tracking_number','LIKE','%'.$tracking_id.'%')->get();

            if(count($order) > 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order Retrieved Successfully',
                    'data' => $order
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No Details found. Try to search again !',
                ]); 
            }
        }

        if(strtoupper($result) == 'WAH')
        {
            $order = Warehousing::where('tracking_number','LIKE','%'.$tracking_id.'%')->get();

            if(count($order) > 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order Retrieved Successfully',
                    'data' => $order
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No Details found. Try to search again !',
                ]); 
            }
        }
           
        return response()->json([
            'success' => false,
            'message' => 'No Details found. Try to search again !',
        ]); 
    }

    public function get_orders_by_order_id($order_id)
    {
        $result = substr($order_id, 0, 7);

        if(strtoupper($result) == 'PUS-ORD')
        {
            $order = PickupService::where('order_id','LIKE','%'.$order_id.'%')->get();

            if(count($order) > 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order Retrieved Successfully',
                    'data' => $order
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No Details found. Try to search again !',
                ]); 
            }
        }

        if(strtoupper($result) == 'ISS-ORD')
        {
            $order = InterStateService::where('order_id','LIKE','%'.$order_id.'%')->get();

            if(count($order) > 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order Retrieved Successfully',
                    'data' => $order
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No Details found. Try to search again !',
                ]); 
            }
        }

        if(strtoupper($result) == 'OSS-ORD')
        {
            $order = OverseaShipping::where('order_id','LIKE','%'.$order_id.'%')->get();

            if(count($order) > 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order Retrieved Successfully',
                    'data' => $order
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No Details found. Try to search again !',
                ]); 
            }
        }

        if(strtoupper($result) == 'PCM-ORD')
        {
            $order = Procurement::where('order_id','LIKE','%'.$order_id.'%')->get();

            if(count($order) > 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order Retrieved Successfully',
                    'data' => $order
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No Details found. Try to search again !',
                ]); 
            }
        }

        if(strtoupper($result) == 'EXS-ORD')
        {
            $order = ExpressShipping::where('order_id','LIKE','%'.$order_id.'%')->get();

            if(count($order) > 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order Retrieved Successfully',
                    'data' => $order
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No Details found. Try to search again !',
                ]); 
            }
        }

        if(strtoupper($result) == 'WAH-ORD')
        {
            $order = Warehousing::where('order_id','LIKE','%'.$order_id.'%')->get();

            if(count($order) > 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order Retrieved Successfully',
                    'data' => $order
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No Details found. Try to search again !',
                ]); 
            }
        }
           
        return response()->json([
            'success' => false,
            'message' => 'No Details found. Try to search again !',
        ]); 
    }

    public function track_order($tracking_id)
    {
        $result = substr($tracking_id, 0, 3);

        if(strtoupper($result) == 'PUS')
        {
            $order = PickupService::where('tracking_number','LIKE','%'.$tracking_id.'%')->get();

            if(count($order) > 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order Retrieved Successfully',
                    'data' => $order
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No Details found. Try to search again !',
                ]); 
            }
        }

        if(strtoupper($result) == 'ISS')
        {
            $order = InterStateService::where('tracking_number','LIKE','%'.$tracking_id.'%')->get();

            if(count($order) > 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order Retrieved Successfully',
                    'data' => $order
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No Details found. Try to search again !',
                ]); 
            }
        }

        if(strtoupper($result) == 'OSS')
        {
            $order = OverseaShipping::where('tracking_number','LIKE','%'.$tracking_id.'%')->get();

            if(count($order) > 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order Retrieved Successfully',
                    'data' => $order
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No Details found. Try to search again !',
                ]); 
            }
        }

        if(strtoupper($result) == 'PCM')
        {
            $order = Procurement::where('tracking_number','LIKE','%'.$tracking_id.'%')->get();

            if(count($order) > 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order Retrieved Successfully',
                    'data' => $order
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No Details found. Try to search again !',
                ]); 
            }
        }

        if(strtoupper($result) == 'EXS')
        {
            $order = ExpressShipping::where('tracking_number','LIKE','%'.$tracking_id.'%')->get();

            if(count($order) > 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order Retrieved Successfully',
                    'data' => $order
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No Details found. Try to search again !',
                ]); 
            }
        }

        if(strtoupper($result) == 'WAH')
        {
            $order = Warehousing::where('tracking_number','LIKE','%'.$tracking_id.'%')->get();

            if(count($order) > 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order Retrieved Successfully',
                    'data' => $order
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No Details found. Try to search again !',
                ]); 
            }
        }
           
        return response()->json([
            'success' => false,
            'message' => 'No Details found. Try to search again !',
        ]); 
    }
}
