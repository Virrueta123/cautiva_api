<?php

namespace App\Models;

use App\Utils\BarcodeGenerator;
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
        "barcode",
        "product_profit"
    ];

    public function category(){
        return $this->belongsTo(category::class, 'category_id');
    }

    public function model(){
        return $this->belongsTo(modell::class, 'model_id');
    }

    // Generar código de barras automáticamente al crear producto
    protected static function booted()
    {
        static::created(function ($product) {
            $generator = new BarcodeGenerator();
            $product->barcode = $generator->generateForProduct($product->product_id);
            $product->save();
        });
    }
}
