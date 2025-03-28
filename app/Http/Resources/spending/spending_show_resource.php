<?php

namespace App\Http\Resources\spending;

use App\Http\Resources\spending_type\spending_type_all_resource;
use App\Http\Resources\spending\spending_payment_details_resource;
use App\Http\Resources\user\user_resource;
use App\Utils\encryptor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class spending_show_resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        
        return [
            'identifier' => encryptor::encrypt($this->spending_id),
            'description' => $this->description,
            'amount' => strval($this->amount),  
            'spending_type' => spending_type_all_resource::make($this->spending_type),
            'created_at' => $this->created_at, 
            'dt_spendings_payment' => spending_payment_details_resource::collection($this->dt_spendings_payments), 
            'user' => user_resource::make($this->user),
        ];
    }
}
