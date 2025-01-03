<?php

namespace App\Http\Resources;

use App\Utils\encryptor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class model_resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'identifier' => encryptor::encrypt($this->model_id),
            'model_name' => $this->model_name,
        ];
    }
}
