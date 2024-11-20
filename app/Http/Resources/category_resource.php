<?php

namespace App\Http\Resources;

use App\Utils\encryptor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class category_resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    { 
        return [
            'identifier' => encryptor::encrypt($this->category_id),
            'category_name' => $this->category_name,
        ];
    }
}
