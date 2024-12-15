<?php
return [
    'cert' => 'certificates/certificate.pem',
    'username_sol' =>  env('USERNAME_SOL'),
    'password_sol' =>  env('PASSWORD_SOL'),
    'ruc_sol' => env('RUC_SOL'),
    "ruc" =>    env('RUC'),
    'razon_social' => env('RAZON_SOCIAL'),
    'nombre_comercial' => env('NOMBRE_COMERCIAL'),
    'address' => [
        'ubigeo' => env('UBIGEO'),
        'departamento' =>  env('DEPARTAMENTO'),
        'provincia' => env('PROVINCIA'),
        'distrito' => env('DISTRITO'),
        'urbanizacion' => env('URBANIZACION'),
        'direccion' => env('DIRECCION'),
        'codigo_localidad' => env('CODIGO_LOCALIDAD'),
    ],
];
