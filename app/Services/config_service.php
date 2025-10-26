<?php

namespace App\services;

use App\Models\config;
use App\Models\config_app;
use App\Utils\number_convert;

class config_service
{
    protected array $configs;

    public function __construct()
    {
        $this->configs = config_app::all()->keyBy('clave')->toArray();
    }

    public function validarClaves(array $requiredKeys): bool|string
    {
        foreach ($requiredKeys as $key) {
            if (!isset($this->configs[$key])) {
                return "Falta la configuración clave: {$key}";
            }
        }
        return true;
    }

    public function obtenerValoresConvertidos(): array
    {
        $promocion_general =  number_convert::precio($this->configs['promocion_general']['precio']);
      

        return [ 
            'promocion_general' => (int) $promocion_general,
        ];
    }

    public function obtenerValoresResponsable(): array
    { 
        return [ 
        ];
    }
}
