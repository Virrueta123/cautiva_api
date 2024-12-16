<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sale extends Model
{
    use HasFactory;
    protected $table = "sales";
    protected $primaryKey = 'sale_id';
    public $timestamps = true;
    public $guarded = [];

    public function dt_sale(){
        return $this->hasMany(dt_sales::class, 'sale_id', 'sale_id');
    }

    public function dt_sales_payments(){
        return $this->hasMany(dt_sales_payments::class, 'sale_id', 'sale_id');
    }
    
}
