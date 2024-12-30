<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class client extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "clients";
    protected $primaryKey = 'client_id';
    public $timestamps = true;
    protected $fillable = [
        'name',
        'lastname',
        'dni',
        'address',
        'ruc',
        'phone',
        'bussiness_name',
        "created_by"
    ];
}
