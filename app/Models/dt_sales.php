<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dt_sales extends Model
{
    use HasFactory;
    protected $table = "dt_sales";
    protected $primaryKey = 'dt_sale_id';
    public $timestamps = true;
    public $guarded = [];
}
