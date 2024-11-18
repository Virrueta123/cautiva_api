<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class modell extends Model
{
    use HasFactory;
    protected $table = "models";
    protected $primaryKey = 'model_id';
    public $timestamps = true;
}
