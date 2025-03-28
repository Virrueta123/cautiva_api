<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class dt_sales_payments extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "dt_sales_payments";
    protected $primaryKey = 'dt_id';
    public $timestamps = true;
    protected $guarded = [];

    public function payment()
    {
        return $this->belongsTo(payment::class, 'payment_id', 'payment_id')->withTrashed();
    }
}
 