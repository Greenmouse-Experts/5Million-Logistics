<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehousing extends Model
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
        'warehouse_location',
        'package_name',
        'package_quantity',
        'package_dimension',
        'package_weight',
        'package_value',
        'package_description',
        'storage_start_date',
        'storage_end_date',
        'owner_full_name',
        'owner_address',
        'owner_phone_number',
        'price',
        'comment',
        'status'
    ];
}
