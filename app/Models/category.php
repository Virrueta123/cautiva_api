<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class category extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "categories";
    protected $primaryKey = 'category_id';
    public $timestamps = true;

    //relationship
    public function products()
    {
        return $this->hasMany(product::class, "products_id");
    }
}
