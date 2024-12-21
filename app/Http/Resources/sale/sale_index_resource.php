<?php

namespace App\Http\Resources\sale;

use App\Utils\encryptor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class sale_index_resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'identifier' => encryptor::encrypt($this->sale_id),
            'tipo_documento' => $this->tipo_documento,
            'serie' => $this->serie,
            'correlativo' => $this->correlativo,
            'setNombre' => $this->setNombre,
            'setApellido' => $this->setApellido,
            "setRazonSocial" => $this->setRazonSocial,
            'created_at' => $this->created_at,  
            'nproducts' => count($this->dt_sale),
            'estado' => $this->estado,
            'total' => $this->total,
        ];
    } 
}
