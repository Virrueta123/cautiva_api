<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class spending extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "spendings";
    protected $primaryKey = 'spending_id';
    public $timestamps = true;
    protected $filllable = ['spending_type_id', 'user_id', 'amount', 'description'];


    public function spending_type()
    {
        return $this->belongsTo(spending_type::class, 'spending_type_id');
    }

    public function dt_spendings_payments()
    {
        return $this->hasMany(dt_sales_spendings::class, 'spending_id');
    }

    public function user()
    {
        return $this->belongsTo(user::class, 'created_by');
    }
}
