<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PickupService extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'tracking_number',
        'pickup_vehicle',
        'pickup_address',
        'dropoff_address',
        'sender_address',
        'sender_name',
        'sender_phone_number',
        'receiver_address',
        'receiver_name',
        'receiver_phone_number',
        'price',
        'comment',
        'status'
    ];
}
