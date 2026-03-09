<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'date_to',
        'date_from',
        'pickupPoints_id',
        'user_id',
        'code',
        'status_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pickupPoint()
    {
        return $this->belongsTo(PickupPoint::class, 'pickupPoints_id');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
