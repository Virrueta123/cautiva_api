<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class spending_type extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "spending_type";
    protected $primaryKey = 'spending_type_id';
    public $timestamps = true;
    protected $guarded = [];
}
