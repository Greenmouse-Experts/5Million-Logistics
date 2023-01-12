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
            $this->mergeWhen($this->service_type == "Pickup", [
                'order' => PickupResource::collection($this->pickup),
            ]),
            $this->mergeWhen($this->service_type == "InterState", [
                'order' => InterStateResource::collection($this->interstate),
            ]),
            $this->mergeWhen($this->service_type == "OverseaShipping", [
                'order' => OverseaShippingResource::collection($this->overseashipping),
            ]),
            $this->mergeWhen($this->service_type == "ExpressShipping", [
                'mergeWhen' => ExpressShippingResource::collection($this->expressshipping),
            ]),
            $this->when($this->service_type == "Procurement", [
                'mergeWhen' => ProcurementResource::collection($this->procurement),
            ]),
            $this->when($this->service_type == "Warehousing", [
                'mergeWhen' => WarehousingResource::collection($this->warehousing),
            ]),
        ];
    }
}
