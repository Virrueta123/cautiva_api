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

    public function sendings_amount()
    {
        return $this->hasMany(payment::class, 'box_id', 'box_id')->where("type_payment","GASTO");
    }

    public function sales_amount()
    {
        return $this->hasMany(payment::class, 'box_id', 'box_id')->where("type_payment","VENTA");
    }
}
