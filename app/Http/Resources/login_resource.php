<?php

namespace App\Http\Resources;

use App\Utils\encryptor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class login_resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'identifier' => encryptor::encrypt($this->id),
            'name' => $this->name,
            'type_user' => $this->type_user,
        ];
    }

    public function with($request)
    {
        return [
            'identifier' =>  $this->id,
            'name' => $this->name,
        ];
    }
}
