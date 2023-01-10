<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderBoard extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'service_id',
        'service_type',
        'assigned_to',
    ];

    public function pickup()
    {
        return $this->hasOne(PickupService::class, 'id', 'service_id');
    }

    public function interstate()
    {
        return $this->hasOne(InterStateService::class, 'id', 'service_id');
    }

    public function overseashipping()
    {
        return $this->hasOne(OverseaShipping::class, 'id', 'service_id');
    }

    public function expressshipping()
    {
        return $this->hasOne(ExpressShipping::class, 'id', 'service_id');
    }

    public function procurement()
    {
        return $this->hasOne(Procurement::class, 'id', 'service_id');
    }

    public function warehousing()
    {
        return $this->hasOne(Warehousing::class, 'id', 'service_id');
    }
}
