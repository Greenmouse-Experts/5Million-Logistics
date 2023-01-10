<?php

namespace App\Http\Resources;

use App\Models\ExpressShipping;
use App\Models\InterStateService;
use App\Models\OverseaShipping;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderBoardResource extends JsonResource
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
            'assigned_to' => $this->assigned_to,
            'pickup_order' => $this->when($this->service_type === "Pickup", new PickupResource($this->pickup)),
            'interstate_order' => $this->when($this->service_type === "InterState", new InterStateResource($this->interstate)),
            'overseashipping_order' => $this->when($this->service_type === "OverseaShipping", new OverseaShippingResource($this->overseashipping)),
            'expressshipping_order' => $this->when($this->service_type === "ExpressShipping", new ExpressShippingResource($this->expressshipping)),
            'procurement_order' => $this->when($this->service_type === "Procurement", new ProcurementResource($this->procurement)),
            'warehousing_order' => $this->when($this->service_type === "Warehousing", new WarehousingResource($this->warehousing)),
        ];
    }
}
