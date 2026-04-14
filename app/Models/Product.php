<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'article',
        'name',
        'price',
        'seller_id',
        'manufacturer_id',
        'category_id',
        'discount',
        'quantity',
        'description',
        'image_path',
    ];

    public function seller(){
        return $this->belongsTo(Seller::class);
    }

    public function manufacturer(){
        return $this->belongsTo(Manufacturer::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }
}
