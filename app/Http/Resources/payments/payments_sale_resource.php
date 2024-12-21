<?php

namespace App\Http\Resources\payments;

use App\Http\Resources\account\account_sale_resource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class payments_sale_resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'amount' => $this->amount,
            'account' => account_sale_resource::make($this->account),
        ];
    }
}
