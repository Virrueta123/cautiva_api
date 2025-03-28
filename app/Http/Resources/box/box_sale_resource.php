<?php

namespace App\Http\Resources\box;

use App\Utils\encryptor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class box_sale_resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [ 
            'identifier' => encryptor::encrypt($this->box_id),
            'reference' => $this->reference,
        ];
    }
}
