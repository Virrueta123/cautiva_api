<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class config extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "configs";
    protected $primaryKey = 'config_id';
    public $timestamps = true;
}
