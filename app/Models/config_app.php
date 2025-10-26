<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class config_app extends Model
{
    use HasFactory;
    protected $table      = "config";
    protected $primaryKey = 'config_id';
    public $timestamps    = true;

    protected $fillable = [
        'clave',
        'label',
        'tipo',
        'cantidad',
        'valor',
        'precio'
    ];
}
