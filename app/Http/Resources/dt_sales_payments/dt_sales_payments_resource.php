<?php

namespace App\Http\Resources\dt_sales_payments;

use App\Http\Resources\payments\payments_sale_resource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class dt_sales_payments_resource extends JsonResource
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
