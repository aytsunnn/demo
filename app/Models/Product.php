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
        'discount',
        'quantity',
        'supplier_id',
        'category_id',
        'manufacturer_id',
        'image_path',
        'description'
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function supplier(){
        return $this->belongsTo(Supplier::class);
    }

    public function manufacturer(){
        return $this->belongsTo(Manufacturer::class);
    }

}
