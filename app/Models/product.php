<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    use HasFactory;
    protected $table = "products";
    protected $primaryKey = 'product_id';
    public $timestamps = true;

    protected $fillable = [
        'category_id',
        'model_id',
        'product_name',
        'product_purchase',
        'product_sales',
        'product_stock',
    ];
}
