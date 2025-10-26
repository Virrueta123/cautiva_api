<?php
namespace App\Utils;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
class number_convert
{
   public static function precio( $value)
   {
       return round((float) $value, 2); 
   }
  
}