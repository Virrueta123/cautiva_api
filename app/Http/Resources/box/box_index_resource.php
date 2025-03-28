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

        $sending_amount = $this->sendings_amount == null ? 0 : $this->sendings_amount->sum("amount");
        $sale_amount = $this->sales_amount == null ? 0 : $this->sales_amount->sum("amount");

        return [
            // Identificador único cifrado
            'identifier' => encryptor::encrypt($this->box_id),

            //nombre de la caja
            'name' => $this->name,

            // Referencia de la caja
            'reference' => $this->reference,

            // Montos en formato string
            'sedings_amount' => strval($sending_amount),
            'sale_amount' => strval($sale_amount),
            'initial_balance' => strval($this->initial_balance),
            'box_amount' =>  strval($sale_amount - $sending_amount),
            'final_balance' => strval(
                $this->status == "C"
                    ? $this->final_balance
                    : $sale_amount - $sending_amount + $this->initial_balance
            ),


            // Fechas relacionadas con la caja
            'opening_date' => $this->opening_date,
            'closing_date' => $this->closing_date,

            // Estado actual de la caja
            'status' => $this->status
        ];
    }
}
