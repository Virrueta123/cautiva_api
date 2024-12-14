<?php

namespace App\Http\Resources\product;

use App\Http\Resources\category_resource;
use App\Http\Resources\model_resource;
use App\Http\Resources\size\select_size_resource;
use App\Http\Resources\user\user_resource;
use App\Utils\encryptor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class show_product_resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'identifier' => encryptor::encrypt($this->product_id), 
            'product_name' => $this->product_name,
            'product_purchase' => $this->product_purchase,
            'product_profit' => $this->product_profit,
            'product_sales' => $this->product_sales,
            'product_stock' => $this->product_stock,
            "barcode" => $this->barcode,
            'created_at' => $this->created_at,
            'category' => category_resource::make($this->category), 
            'model' => model_resource::make($this->model),
            'user' => user_resource::make($this->user),
            'size' => select_size_resource::make($this->size),
        ];
    }
}
