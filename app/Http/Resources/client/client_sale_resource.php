<?php

namespace App\Http\Resources\client;

use App\Utils\encryptor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class client_sale_resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
            'identifier' => encryptor::encrypt($this->client_id),
            'name' => $this->name,
            'lastname' => $this->lastname,
            'dni' => $this->dni,
            'address' => $this->address, 
            'ruc' => $this->ruc ,
            'phone' => $this->phone,
            'bussiness_name' => $this->bussiness_name, 
        ];
    }
}
