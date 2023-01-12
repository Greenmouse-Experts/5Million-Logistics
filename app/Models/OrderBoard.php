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
        return $this->hasMany(PickupService::class, 'id', 'service_id');
    }

    public function interstate()
    {
        return $this->hasMany(InterStateService::class, 'id', 'service_id');
    }

    public function overseashipping()
    {
        return $this->hasMany(OverseaShipping::class, 'id', 'service_id');
    }

    public function expressshipping()
    {
        return $this->hasMany(ExpressShipping::class, 'id', 'service_id');
    }

    public function procurement()
    {
        return $this->hasMany(Procurement::class, 'id', 'service_id');
    }

    public function warehousing()
    {
        return $this->hasMany(Warehousing::class, 'id', 'service_id');
    }
}
