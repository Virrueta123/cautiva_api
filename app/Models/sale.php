<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class sale extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "sales";
    protected $primaryKey = 'sale_id';
    public $timestamps = true;
    public $guarded = [];

    public function dt_sale()
    {
        return $this->hasMany(dt_sales::class, 'sale_id', 'sale_id');
    }

    public function dt_sales_payments()
    {
        return $this->hasMany(dt_sales_payments::class, 'sale_id', 'sale_id');
    }

    public function client()
    {
        return $this->belongsTo(client::class, 'client_id', 'client_id');
    }

    public function box()
    {
        return $this->belongsTo(box::class, 'box_id', 'box_id');
    }

    public function user()
    {
        return $this->belongsTo(user::class, 'created_by');
    }
}
