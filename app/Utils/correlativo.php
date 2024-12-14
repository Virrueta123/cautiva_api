<?php
namespace App\Utils;

class correlativo
{
    static function formatoCorrelativo($numero) {
        return str_pad($numero, 3, '0', STR_PAD_LEFT);
    }
}
