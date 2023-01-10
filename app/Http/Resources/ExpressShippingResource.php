<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpressShippingResource extends JsonResource
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
            'freight_service' => $this->freight_service,
            'owner_full_name' => $this->owner_full_name,
            'owner_address' => $this->owner_address,
            'owner_email' => $this->owner_email,
            'owner_phone_number' => $this->owner_phone_number,
            'date_of_shipment' => $this->date_of_shipment,
            'shipping_from_street_address' => $this->shipping_from_street_address,
            'shipping_from_city' => $this->shipping_from_city,
            'shipping_from_state_province_region' => $this->shipping_from_state_province_region,
            'shipping_from_zip_portal_code' => $this->shipping_from_zip_portal_code,
            'shipping_from_country' => $this->shipping_from_country,
            'shipping_to_street_address' => $this->shipping_to_street_address,
            'shipping_to_city' => $this->shipping_to_city,
            'shipping_to_state_province_region' => $this->shipping_to_state_province_region,
            'shipping_to_zip_portal_code' => $this->shipping_to_zip_portal_code,
            'shipping_to_country' => $this->shipping_to_country,
            'package_name' => $this->package_name,
            'package_quantity' => $this->package_quantity,
            'package_dimension' => $this->package_dimension,
            'package_weight' => $this->package_weight,
            'package_value' => $this->package_value,
            'package_description' => $this->package_description,
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
