<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'date_order',
        'date_delivery',
        'pickup_point_id',
        'user_id',
        'code',
        'status_id',
    ];

    public function pickup_point(){
        return $this->belongsTo(PickupPoint::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function status(){
        return $this->belongsTo(Status::class);
    }

    public function details(){
        return $this->hasMany(OrderDetails::class);
    }
}
