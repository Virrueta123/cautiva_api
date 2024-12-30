<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class size extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "sizes";
    protected $primaryKey = 'size_id';
    public $timestamps = true;
}
