<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class modell extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "models";
    protected $primaryKey = 'model_id';
    public $timestamps = true;
}
