<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PickupResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'service_type' => $this->service_type,
            'order_id' => $this->order_id,
            'tracking_number' => $this->tracking_number,
            'pickup_vehicle' => $this->pickup_vehicle,
            'pickup_address' => $this->pickup_address,
            'dropoff_address' => $this->dropoff_address,
            'sender_address' => $this->sender_address,
            'sender_name' => $this->sender_name,
            'sender_phone_number' => $this->sender_phone_number,
            'receiver_address' => $this->receiver_address,
            'receiver_name' => $this->receiver_name,
            'receiver_phone_number' => $this->receiver_phone_number,
            'price' => $this->price,
            'comment' => $this->comment,
            'status' => $this->status,
            'progress' => $this->progress,
            'estimated_delivery_time' => $this->estimated_delivery_time,
            'current_location' => $this->current_location,
            'created_at' => $this->created_at,
            'order_by' => new UserResource($this->user),
        ];
    }
}
