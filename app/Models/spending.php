<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class spending extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "speding";
    protected $primaryKey = 'speding_id';
    public $timestamps = true;
    protected $guarded = [];
}
