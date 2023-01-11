<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExpressShippingResource;
use App\Http\Resources\InterStateResource;
use App\Http\Resources\OrderBoardResource;
use App\Http\Resources\OverseaShippingResource;
use App\Http\Resources\PickupResource;
use App\Http\Resources\ProcurementResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\WarehousingResource;
use App\Models\ExpressShipping;
use App\Models\InterStateService;
use App\Models\OrderBoard;
use App\Models\OverseaShipping;
use App\Models\PickupService;
use App\Models\Procurement;
use App\Models\User;
use App\Models\Warehousing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

    public function get_user($id)
    {
        $user = User::find($id);

        if($user)
        {
            return response()->json([
                'success' => true,
                'message' => 'User Retrieved Successfully!',
                'data' => new UserResource($user)
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'User Not Found'
        ]);
    }

    public function user_deactivate($id)
    {
        $user = User::find($id);

        $user->update([
            'status' => 'Inactive'
        ]);

        return response()->json([
            'success' => true,
            'message' => $user->first_name.' '.$user->last_name . ' Account Deactivated!',
            'data' => $user
        ]);
    }

    public function user_activate($id)
    {
        $user = User::find($id);

        $user->update([
            'status' => 'Active'
        ]);

        return response()->json([
            'success' => true,
            'message' => $user->first_name.' '.$user->last_name . ' Account Activated!',
            'data' => $user
        ]);
    }

    public function user_change_password($id, Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'new_password' => ['required', 'string', 'min:8', 'confirmed']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please see errors parameter for all errors.',
                'errors' => $validator->errors()
            ]);
        }

        $user = User::findorfail($id);
        
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => $user->first_name.' '.$user->last_name. ' Password Updated.',
            'data' => $user
        ]);
    }

    public function update_user_profile($id, Request $request)
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

        $user = User::findorfail($id);
        
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

            return response()->json([
                'success' => true,
                'message' => $user->first_name. ' ' .$user->last_name. ' Profile Updated Successfully!',
                'data' => $user
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

            return response()->json([
                'success' => true,
                'message' => $user->first_name. ' ' .$user->last_name. ' Profile Updated Successfully!',
                'data' => $user
            ]);
        }
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

    public function update_order($order_id, Request $request)
    {
        $result = substr($order_id, 0, 7);

        if(strtoupper($result) == 'PUS-ORD')
        {
            $validator = Validator::make(request()->all(), [
                'status' => 'required|string|max:244|min:1',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please see errors parameter for all errors.',
                    'errors' => $validator->errors()
                ]);
            }

            $order = PickupService::where('order_id',$order_id)->first();

            if($order) {

                $order->update([
                    'comment' => $request->comment,
                    'status' => $request->status,
                    'progress' => $request->progress,
                    'estimated_delivery_time' => $request->estimated_delivery_time,
                    'current_location' => $request->current_location,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Order updated successfully.',
                    'data' => $order
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Details not found in our database.',
                ]); 
            }
        }

        if(strtoupper($result) == 'ISS-ORD')
        {
            $validator = Validator::make(request()->all(), [
                'status' => 'required|string|max:244|min:1',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please see errors parameter for all errors.',
                    'errors' => $validator->errors()
                ]);
            }

            $order = InterStateService::where('order_id',$order_id)->first();

            if($order) {

                $order->update([
                    'comment' => $request->comment,
                    'status' => $request->status,
                    'progress' => $request->progress,
                    'estimated_delivery_time' => $request->estimated_delivery_time,
                    'current_location' => $request->current_location,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Order updated successfully.',
                    'data' => $order
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Details not found in our database.',
                ]); 
            }
        }

        if(strtoupper($result) == 'OSS-ORD')
        {
            $validator = Validator::make(request()->all(), [
                'status' => 'required|string|max:244|min:1',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please see errors parameter for all errors.',
                    'errors' => $validator->errors()
                ]);
            }

            $order = OverseaShipping::where('order_id',$order_id)->first();

            if($order) {

                $order->update([
                    'comment' => $request->comment,
                    'status' => $request->status,
                    'progress' => $request->progress,
                    'estimated_delivery_time' => $request->estimated_delivery_time,
                    'current_location' => $request->current_location,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Order updated successfully.',
                    'data' => $order
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Details not found in our database.',
                ]); 
            }
        }

        if(strtoupper($result) == 'PCM-ORD')
        {
            $validator = Validator::make(request()->all(), [
                'status' => 'required|string|max:244|min:1',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please see errors parameter for all errors.',
                    'errors' => $validator->errors()
                ]);
            }

            $order = Procurement::where('order_id',$order_id)->first();

            if($order) {

                $order->update([
                    'comment' => $request->comment,
                    'status' => $request->status,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Order updated successfully.',
                    'data' => $order
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Details not found in our database.',
                ]); 
            }
        }

        if(strtoupper($result) == 'EXS-ORD')
        {
            $validator = Validator::make(request()->all(), [
                'status' => 'required|string|max:244|min:1',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please see errors parameter for all errors.',
                    'errors' => $validator->errors()
                ]);
            }

            $order = ExpressShipping::where('order_id',$order_id)->first();

            if($order) {

                $order->update([
                    'comment' => $request->comment,
                    'status' => $request->status,
                    'progress' => $request->progress,
                    'estimated_delivery_time' => $request->estimated_delivery_time,
                    'current_location' => $request->current_location,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Order updated successfully.',
                    'data' => $order
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Details not found in our database.',
                ]); 
            }
        }

        if(strtoupper($result) == 'WAH-ORD')
        {
            $validator = Validator::make(request()->all(), [
                'status' => 'required|string|max:244|min:1',
            ]);
    
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please see errors parameter for all errors.',
                    'errors' => $validator->errors()
                ]);
            }
            
            $order = Warehousing::where('order_id',$order_id)->first();

            if($order) {

                $order->update([
                    'comment' => $request->comment,
                    'status' => $request->status,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Order updated successfully.',
                    'data' => $order
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Details not found in our database.',
                ]); 
            }
        }
           
        return response()->json([
            'success' => false,
            'message' => 'Details not found in our database.',
        ]); 
    }

    public function dispatch_order($order_id)
    {
        $result = substr($order_id, 0, 7);

        if(strtoupper($result) == 'PUS-ORD')
        {
            $boards = OrderBoard::where('service_type', 'Pickup')->get();
            
            $order = PickupService::where('order_id', $order_id)->first();

            if($boards->isEmpty())
            {
                if($order) {
                    $orderboard = OrderBoard::create([
                        'service_id' => $order->id,
                        'service_type' => $order->service_type
                    ]);

                    $order->update([
                        'status' => 'Dispatch'
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Order Dispatched Successfully',
                        'data' => new OrderBoardResource($orderboard)
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'No Details found!',
                    ]); 
                }   
            } else {
                foreach($boards as $board)
                {
                    $serviceID[] = $board->service_id; 
                }
                if (in_array($order->id, $serviceID)) 
                {
                    return response()->json([
                        'success' => false,
                        'message' => 'This order has been dispatched before.',
                    ]); 
                } else {
                    if($order) {
                        $orderboard = OrderBoard::create([
                            'service_id' => $order->id,
                            'service_type' => $order->service_type
                        ]);
    
                        $order->update([
                            'status' => 'Dispatch'
                        ]);
    
                        return response()->json([
                            'success' => true,
                            'message' => 'Order Dispatched Successfully',
                            'data' => new OrderBoardResource($orderboard)
                        ]);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'No Details found!',
                        ]); 
                    } 
                }
            }
        }

        if(strtoupper($result) == 'ISS-ORD')
        {
            $boards = OrderBoard::where('service_type', 'InterState')->get();

            $order = InterStateService::where('order_id', $order_id)->first();
            
            if($boards->isEmpty())
            {
                if($order) {
                    $orderboard = OrderBoard::create([
                        'service_id' => $order->id,
                        'service_type' => $order->service_type
                    ]);

                    $order->update([
                        'status' => 'Dispatch'
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Order Dispatched Successfully',
                        'data' => new OrderBoardResource($orderboard)
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'No Details found!',
                    ]); 
                }
            } else {
                foreach($boards as $board)
                {
                    $serviceID[] = $board->service_id; 
                }
                if (in_array($order->id, $serviceID)) 
                {
                    return response()->json([
                        'success' => false,
                        'message' => 'This order has been dispatched before.',
                    ]); 
                } else {
                    if($order) {
                        $orderboard = OrderBoard::create([
                            'service_id' => $order->id,
                            'service_type' => $order->service_type
                        ]);
    
                        $order->update([
                            'status' => 'Dispatch'
                        ]);
    
                        return response()->json([
                            'success' => true,
                            'message' => 'Order Dispatched Successfully',
                            'data' => new OrderBoardResource($orderboard)
                        ]);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'No Details found!',
                        ]); 
                    }
                }
            }
        }

        if(strtoupper($result) == 'OSS-ORD')
        {
            $boards = OrderBoard::where('service_type', 'OverseaShipping')->get();

            $order = OverseaShipping::where('order_id', $order_id)->first();

            if($boards->isEmpty())
            {
                if($order) {
                    $orderboard = OrderBoard::create([
                        'service_id' => $order->id,
                        'service_type' => $order->service_type
                    ]);

                    $order->update([
                        'status' => 'Dispatch'
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Order Dispatched Successfully',
                        'data' => new OrderBoardResource($orderboard)
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'No Details found!',
                    ]); 
                }
            } else {
                foreach($boards as $board)
                {
                    $serviceID[] = $board->service_id; 
                }
                if (in_array($order->id, $serviceID)) 
                {
                    return response()->json([
                        'success' => false,
                        'message' => 'This order has been dispatched before.',
                    ]); 
                } else {
                    if($order) {
                        $orderboard = OrderBoard::create([
                            'service_id' => $order->id,
                            'service_type' => $order->service_type
                        ]);
    
                        $order->update([
                            'status' => 'Dispatch'
                        ]);
    
                        return response()->json([
                            'success' => true,
                            'message' => 'Order Dispatched Successfully',
                            'data' => new OrderBoardResource($orderboard)
                        ]);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'No Details found!',
                        ]); 
                    }
                }
            }
        }

        if(strtoupper($result) == 'PCM-ORD')
        {
            $boards = OrderBoard::where('service_type', 'Procurement')->get();

            $order = Procurement::where('order_id', $order_id)->first();

            if($boards->isEmpty())
            {
                if($order) {
                    $orderboard = OrderBoard::create([
                        'service_id' => $order->id,
                        'service_type' => $order->service_type
                    ]);

                    $order->update([
                        'status' => 'Dispatch'
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Order Dispatched Successfully',
                        'data' => new OrderBoardResource($orderboard)
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'No Details found!',
                    ]); 
                }
            } else {
                foreach($boards as $board)
                {
                    $serviceID[] = $board->service_id; 
                }
                if (in_array($order->id, $serviceID)) 
                {
                    return response()->json([
                        'success' => false,
                        'message' => 'This order has been dispatched before.',
                    ]); 
                } else {
                    if($order) {
                        $orderboard = OrderBoard::create([
                            'service_id' => $order->id,
                            'service_type' => $order->service_type
                        ]);
    
                        $order->update([
                            'status' => 'Dispatch'
                        ]);
    
                        return response()->json([
                            'success' => true,
                            'message' => 'Order Dispatched Successfully',
                            'data' => new OrderBoardResource($orderboard)
                        ]);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'No Details found!',
                        ]); 
                    }
                }
            }
        }

        if(strtoupper($result) == 'EXS-ORD')
        {
            $boards = OrderBoard::where('service_type', 'ExpressShipping')->get();

            $order = ExpressShipping::where('order_id', $order_id)->first();

            if($boards->isEmpty())
            {
                if($order) {
                    $orderboard = OrderBoard::create([
                        'service_id' => $order->id,
                        'service_type' => $order->service_type
                    ]);

                    $order->update([
                        'status' => 'Dispatch'
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Order Dispatched Successfully',
                        'data' => new OrderBoardResource($orderboard)
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'No Details found!',
                    ]); 
                }
            } else {
                foreach($boards as $board)
                {
                    $serviceID[] = $board->service_id; 
                }
                if (in_array($order->id, $serviceID)) 
                {
                    return response()->json([
                        'success' => false,
                        'message' => 'This order has been dispatched before.',
                    ]); 
                } else {
                    if($order) {
                        $orderboard = OrderBoard::create([
                            'service_id' => $order->id,
                            'service_type' => $order->service_type
                        ]);
    
                        $order->update([
                            'status' => 'Dispatch'
                        ]);
    
                        return response()->json([
                            'success' => true,
                            'message' => 'Order Dispatched Successfully',
                            'data' => new OrderBoardResource($orderboard)
                        ]);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'No Details found!',
                        ]); 
                    }
                }
            }
        }

        if(strtoupper($result) == 'WAH-ORD')
        {
            $boards = OrderBoard::where('service_type', 'Warehousing')->get();

            $order = Warehousing::where('order_id', $order_id)->first();

            if($boards->isEmpty())
            {
                if($order) {
                    $orderboard = OrderBoard::create([
                        'service_id' => $order->id,
                        'service_type' => $order->service_type
                    ]);

                    $order->update([
                        'status' => 'Dispatch'
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Order Dispatched Successfully',
                        'data' => new OrderBoardResource($orderboard)
                    ]);

                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'No Details found!',
                    ]); 
                }
            } else {
                foreach($boards as $board)
                {
                    $serviceID[] = $board->service_id; 
                }
                if (in_array($order->id, $serviceID)) 
                {
                    return response()->json([
                        'success' => false,
                        'message' => 'This order has been dispatched before.',
                    ]); 
                } else {
                    if($order) {
                        $orderboard = OrderBoard::create([
                            'service_id' => $order->id,
                            'service_type' => $order->service_type
                        ]);
    
                        $order->update([
                            'status' => 'Dispatch'
                        ]);
    
                        return response()->json([
                            'success' => true,
                            'message' => 'Order Dispatched Successfully',
                            'data' => new OrderBoardResource($orderboard)
                        ]);
    
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'No Details found!',
                        ]); 
                    }
                }
            }
        }
           
        return response()->json([
            'success' => false,
            'message' => "Order ID can't be found in our database!",
        ]); 
    }
}
