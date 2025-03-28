<?php

namespace App\Http\Resources\account;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class account_resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [ 
            'account_name' => $this->account_name, 
        ];
    }
}
