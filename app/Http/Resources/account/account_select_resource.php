<?php

namespace App\Http\Resources\account;

use App\Utils\encryptor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class account_select_resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'identifier' => encryptor::encrypt($this->account_id),
            'account_name' => $this->account_name,
            'is_default' => $this->is_default,
        ];
    }
}
