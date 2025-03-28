<?php

namespace App\Http\Resources\payment;

use App\Http\Resources\account\account_resource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class payment_resource extends JsonResource
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
            'account' => account_resource::make($this->account),
        ];
    }
}
