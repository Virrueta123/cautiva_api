<?php

namespace App\Http\Resources\spending_type;

use App\Utils\encryptor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class spending_type_all_resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [
            'identifier' => encryptor::encrypt($this->spending_type_id),
            'spending_type_name' => $this->spending_type_name, 
            'created_at' => $this->created_at,
        ];
    }
}
