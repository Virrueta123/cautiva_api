<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class payment extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "payments";
    protected $primaryKey = 'payment_id';
    public $timestamps = true;

    public function account()
    {
        return $this->hasOne(account::class, 'account_id', 'account_id');
    }
}
