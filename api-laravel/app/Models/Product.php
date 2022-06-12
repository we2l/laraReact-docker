<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'image', 'minimum_stock', 'sale_price', 'purchase_price'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category');
    }
}
