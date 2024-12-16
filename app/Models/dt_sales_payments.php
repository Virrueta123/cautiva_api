<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dt_sales_payments extends Model
{
    use HasFactory;
    protected $table = "dt_sales_payments";
    protected $primaryKey = 'dt_id'; 

    public function payments(){
        return $this->hasMany(payment::class, 'payment_id', 'payment_id');
    }
}
