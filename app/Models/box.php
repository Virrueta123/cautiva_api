<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class box extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "boxes";
    protected $primaryKey = 'box_id';
    public $timestamps = true;
    protected $fillable = [
        'initial_balance',
        'reference',
        'created_by',
        'status',
        'opening_date'
    ];

    public function box_sale()
    {
        return $this->hasMany(sale::class, 'box_id', 'box_id');
    }

    public function spending_amount()
    {
        return $this->hasMany(payment::class, 'box_id', 'box_id')->where("type_payment", "GASTO");
    }

    public function sales_amount()
    {
        return $this->hasMany(payment::class, 'box_id', 'box_id')->where("type_payment", "VENTA");
    }

    public function box_sales()
    {
        // all sales 
        return $this->hasMany(sale::class, 'box_id', 'box_id');
    }

    // para calcular todo los ingresos en efectivo
    public function sale_account($is_cash = true)
    {
        return $this->hasMany(payment::class, 'box_id', 'box_id')
            ->where("type_payment", "VENTA")
            ->whereHas('account', function ($query) use ($is_cash) {
                $query->where("is_default", $is_cash ? "Y" : "N");
            });
    }

    public function spending_account($is_cash = true)
    {
        return $this->hasMany(payment::class, 'box_id', 'box_id')
            ->where("type_payment", "GASTO")
            ->whereHas('account', function ($query) use ($is_cash) {
                $query->where("is_default", $is_cash ? "Y" : "N");
            });
    }

    public static function boot()
    {
        parent::boot();

        // Generar nombre único al crear una nueva caja
        static::creating(function ($model) {
            do {
                $nameUnique = 'Caja-' . uniqid();
            } while (self::where('name', $nameUnique)->exists());

            $model->name = $model->name ?? $nameUnique;
        });
    }
}
