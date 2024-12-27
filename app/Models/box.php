<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class box extends Model
{
    use HasFactory;
    protected $table = "boxes";
    protected $primaryKey = 'box_id';
    public $timestamps = true;
    protected $fillable = [
        'initial_balance',
        'reference',
        'created_by',
        'status',
        'opening_date'

    ];

    public function sendings()
    {
         
    }
}
