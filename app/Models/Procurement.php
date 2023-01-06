<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procurement extends Model
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
        'item_name',
        'item_type',
        'item_store_name',
        'item_description',
        'item_tracking_id',
        'item_value',
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
        'price',
        'comment',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
