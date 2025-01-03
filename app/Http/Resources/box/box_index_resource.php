<?php

namespace App\Http\Resources\box;

use App\Utils\encryptor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class box_index_resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'identifier' => encryptor::encrypt($this->box_id),
            'reference' => $this->reference,
            'sedings_amount' =>  $this->sendings_amount->sum('amount'),
            'sale_amount' =>  $this->sales_amount->sum('amount'),
            "initial_balance" => $this->initial_balance,
            "opening_date" => $this->opening_date,
            "total" =>  $this->sales_amount->sum('amount') -  $this->sendings_amount->sum('amount'), 
        ];
    }
}
