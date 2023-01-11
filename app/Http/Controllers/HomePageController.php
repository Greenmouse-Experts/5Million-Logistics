<?php

namespace App\Http\Controllers;

use App\Models\ExpressShipping;
use App\Models\InterStateService;
use App\Models\OverseaShipping;
use App\Models\PickupService;
use App\Models\Procurement;
use App\Models\Warehousing;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
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
