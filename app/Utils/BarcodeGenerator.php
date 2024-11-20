<?php

namespace App\Utils; 

class BarcodeGenerator 
{
    private const PERU_PREFIX = '775';
    // Array de códigos de negocio disponibles
    private const BUSINESS_CODES = [
        '0001', // Para productos 0-99999
        '0002', // Para productos 100000-199999
        '0003', // Para productos 200000-299999
        '0004', // Para productos 300000-399999
        '0005', // Para productos 400000-499999
        '0006', // Para productos 500000-599999
        '0007', // Para productos 600000-699999
        '0008', // Para productos 700000-799999
        '0009', // Para productos 800000-899999
        '0010', // Para productos 900000-999999
        '0011', // Para productos 1000000-1099999
        '0012', // Para productos 1100000-1199999
        '0013', // Para productos 1200000-1299999
        '0014', // Para productos 1300000-1399999
        '0015', // Para productos 1400000-1499999
        '0016', // Para productos 1500000-1599999
        '0017', // Para productos 1600000-1699999
        '0018', // Para productos 1700000-1799999
        '0019', // Para productos 1800000-1899999
        '0020', // Para productos 1900000-1999999
        '0021', // Para productos 2000000-2099999
        '0022', // Para productos 2100000-2199999
        '0023', // Para productos 2200000-2299999
        '0024', // Para productos 2300000-2399999
        '0025', // Para productos 2400000-2499999
        '0026', // Para productos 2500000-2599999
        '0027', // Para productos 2600000-2699999
        '0028', // Para productos 2700000-2799999
        '0029', // Para productos 2800000-2899999
        '0030', // Para productos 2900000-2999999
        '0031', // Para productos 3000000-3099999
        '0032', // Para productos 3100000-3199999
        '0033', // Para productos 3200000-3299999
        '0034', // Para productos 3300000-3399999
        '0035', // Para productos 3400000-3499999
        '0036', // Para productos 3500000-3599999
        '0037', // Para productos 3600000-3699999
        '0038', // Para productos 3700000-3799999
        '0039', // Para productos 3800000-3899999
        '0040', // Para productos 3900000-3999999
        '0041', // Para productos 4000000-4099999
        '0042', // Para productos 4100000-4199999
        '0043', // Para productos 4200000-4299999
        '0044', // Para productos 4300000-4399999
        '0045', // Para productos 4400000-4499999
        '0046', // Para productos 4500000-4599999
        '0047', // Para productos 4600000-4699999
        '0048', // Para productos 4700000-4799999
        '0049', // Para productos 4800000-4899999
        '0050', // Para productos 4900000-4999999
        '0051', // Para productos 5000000-5099999
        '0052', // Para productos 5100000-5199999
        '0053', // Para productos 5200000-5299999
        '0054', // Para productos 5300000-5399999
        '0055', // Para productos 5400000-5499999
        '0056', // Para productos 5500000-5599999
        '0057', // Para productos 5600000-5699999
        '0058', // Para productos 5700000-5799999
        '0059', // Para productos 5800000-5899999
        '0060', // Para productos 5900000-5999999
        '0061', // Para productos 6000000-6099999
        '0062', // Para productos 6100000-6199999
        '0063', // Para productos 6200000-6299999
        '0064', // Para productos 6300000-6399999
        '0065', // Para productos 6400000-6499999
        '0066', // Para productos 6500000-6599999
        '0067', // Para productos 6600000-6699999
        '0068', // Para productos 6700000-6799999
        '0069', // Para productos 6800000-6899999
        '0070', // Para productos 6900000-6999999
        '0071', // Para productos 7000000-7099999
        '0072', // Para productos 7100000-7199999
        '0073', // Para productos 7200000-7299999
        '0074', // Para productos 7300000-7399999
        '0075', // Para productos 7400000-7499999
        '0076', // Para productos 7500000-7599999
        '0077', // Para productos 7600000-7699999
        '0078', // Para productos 7700000-7799999
        '0079', // Para productos 7800000-7899999
        '0080', // Para productos 7900000-7999999
        '0081', // Para productos 8000000-8099999
        '0082', // Para productos 8100000-8199999
        '0083', // Para productos 8200000-8299999
        '0084', // Para productos 8300000-8399999
        '0085', // Para productos 8400000-8499999
        '0086', // Para productos 8500000-8599999
        '0087', // Para productos 8600000-8699999
        '0088', // Para productos 8700000-8799999
        '0089', // Para productos 8800000-8899999
        '0090'  // Para productos 8900000-8999999
    ];

    public function generateForProduct($productId)
    {
        // Determinar qué código de negocio usar basado en el ID
        $businessCodeIndex = 0; // Siempre usamos el primer código de negocio para empezar
        
        if ($businessCodeIndex >= count(self::BUSINESS_CODES)) {
            throw new \Exception("ID de producto excede el límite máximo permitido");
        }

        // Obtener el código de negocio correspondiente
        $businessCode = self::BUSINESS_CODES[$businessCodeIndex];
        
        // Usar directamente el product_id para el código
        $productCode = str_pad($productId, 5, '0', STR_PAD_LEFT);
        
        // Construir el código
        $code = self::PERU_PREFIX . $businessCode . $productCode;
        
        // Añadir dígito de control
        return $code . $this->calculateCheckDigit($code);
    }

    private function calculateCheckDigit($code12)
    {
        // Revertimos el string para facilitar el cálculo
        $code12 = strrev($code12);
        $sum = 0;
        
        // Iteramos sobre cada dígito
        for ($i = 0; $i < strlen($code12); $i++) {
            // Multiplicamos por 3 las posiciones pares (0-based)
            $multiplier = ($i % 2 == 0) ? 3 : 1;
            $sum += intval($code12[$i]) * $multiplier;
        }
        
        // Calculamos el dígito de verificación
        $checkDigit = (10 - ($sum % 10)) % 10;
        
        return $checkDigit;
    }
}