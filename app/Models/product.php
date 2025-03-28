<?php

namespace App\Models;

use App\Utils\BarcodeGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class product extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "products";
    protected $primaryKey = 'product_id';
    public $timestamps = true;

    protected $fillable = [
        'category_id',
        'model_id',
        'size_id',
        'product_name',
        'product_purchase',
        'product_sales',
        'product_stock',
        "barcode",
        "product_profit",
        "created_by",
        "is_hot_sale"
    ];

    public function category()
    {
        return $this->belongsTo(category::class, 'category_id');
    }

    public function model()
    {
        return $this->belongsTo(modell::class, 'model_id');
    }

    public function user()
    {
        return $this->belongsTo(user::class, 'created_by');
    }

    public function size()
    {
        return $this->belongsTo(size::class, 'size_id');
    }

    // Generar código de barras automáticamente al crear producto
    protected static function booted()
    {
        static::creating(function ($product) {
            // Intentar generar un código único
            do {
                // Tomar las primeras dos letras del nombre del producto, categoría y modelo
                $productName = strtoupper(substr($product->product_name ?? 'XX', 0, 2));
                $typeInitials = strtoupper(substr($product->category->category_name ?? 'XX', 0, 2));
                $sizeInitials = strtoupper(substr($product->model->model_name ?? 'XX', 0, 2));

                // Generar un número aleatorio
                $randomNumber = random_int(1000, 9999);

                // Concatenar el código
                $barcode = $productName . $typeInitials . $sizeInitials . $randomNumber;

                // Verificar si el código ya existe
            } while (self::where('barcode', $barcode)->exists());

            // Asignar el código único al producto
            $product->barcode = $barcode;
        });
    }
}
