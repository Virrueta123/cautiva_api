<?php

namespace App\Http\Resources\spending;

use App\Http\Resources\payment\payment_resource;
use App\Http\Resources\payments\payments_sale_resource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class spending_payment_details_resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
         return [ 
            "payment" => payments_sale_resource::make($this->payment),
        ];
    }
}
