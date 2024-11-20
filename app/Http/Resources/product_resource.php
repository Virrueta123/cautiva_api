<?php

namespace App\Http\Resources;

use App\Utils\Encryptor;
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
            'identifier' => Encryptor::encrypt($this->product_id), 
            'product_name' => $this->product_name,
            'product_purchase' => $this->product_purchase,
            'product_profit' => $this->product_profit,
            'product_sales' => $this->product_sales,
            'product_stock' => $this->product_stock,
            "barcode" => $this->barcode,
            'created_at' => $this->created_at,
            'category' => category_resource::make($this->category), 
            'model' => model_resource::make($this->model)
        ];
    }
}
