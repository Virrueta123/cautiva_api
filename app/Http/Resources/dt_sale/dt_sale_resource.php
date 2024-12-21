<?php

namespace App\Http\Resources\dt_sale;

use App\Http\Resources\product\show_product_resource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class dt_sale_resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'MtoPrecioUnitario' => $this->MtoPrecioUnitario,
            'discount' => $this->discount,
            "product" => show_product_resource::make($this->product),
        ];
    }
}
