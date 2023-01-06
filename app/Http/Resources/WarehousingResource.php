<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WarehousingResource extends JsonResource
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
            'warehouse_location' => $this->warehouse_location,
            'package_name' => $this->package_name,
            'package_quantity' => $this->package_quantity,
            'package_dimension' => $this->package_dimension,
            'package_weight' => $this->package_weight,
            'package_value' => $this->package_value,
            'package_description' => $this->package_description,
            'storage_start_date' => $this->storage_start_date,
            'storage_end_date' => $this->storage_end_date,
            'owner_full_name' => $this->owner_full_name,
            'owner_address' => $this->owner_address,
            'owner_phone_number' => $this->owner_phone_number,
            'price' => $this->price,
            'comment' => $this->comment,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'order_by' => new UserResource($this->user),
        ];
    }
}
