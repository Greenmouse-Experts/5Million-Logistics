<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OverseaShipping extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'service_type',
        'order_id',
        'tracking_number',
        'freight_service',
        'owner_full_name',
        'owner_address',
        'owner_email',
        'owner_phone_number',
        'date_of_shipment',
        'shipping_from_street_address',
        'shipping_from_city',
        'shipping_from_state_province_region',
        'shipping_from_zip_portal_code',
        'shipping_from_country',
        'shipping_to_street_address',
        'shipping_to_city',
        'shipping_to_state_province_region',
        'shipping_to_zip_portal_code',
        'shipping_to_country',
        'package_name',
        'package_quantity',
        'package_dimension',
        'package_weight',
        'package_value',
        'package_description',
        'price',
        'comment',
        'status',
        'progress',
        'current_location'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
