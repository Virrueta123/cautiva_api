<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class product_resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'identifier' =>  $this->product_id,
            'category_id' => $this->category_id,
            'model_id' => $this->model_id,
            'product_name' => $this->product_name,
            'product_purchase' => $this->product_purchase,
            'product_sales' => $this->product_sales,
            'product_stock' => $this->product_stock,
        ];
    }
}
