<?php

namespace App\Http\Resources\box;

use App\Http\Resources\sale\sale_index_resource;
use App\Utils\encryptor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class box_details_sale_resource extends JsonResource
{
    protected $paymentAccountGeneral;

    /**
     * Constructor para recibir datos adicionales.
     */
    public function __construct($resource, $paymentAccountGeneral)
    {
        // Asigna los datos adicionales
        parent::__construct($resource);
        $this->paymentAccountGeneral = $paymentAccountGeneral;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $salesTotal = $this->sales_amount->sum('amount');
        $saleCashTotal = $this->sale_account(true)->sum('amount');
        $saleAccountTotal = $this->sale_account(false)->sum('amount');
        return [
            'identifier' => encryptor::encrypt($this->box_id),
            'reference' => $this->reference,
            'name' => $this->name,
            // Total sales amount as a string
            'sales_amount' => strval($salesTotal),
            // Total sales by payment type (cash and account) as strings
            'sale_payment_cash' => strval($saleCashTotal),
            'sale_payment_account' => strval($saleAccountTotal),
            'account_general' => $this->paymentAccountGeneral,
            'box_sale' =>  sale_index_resource::collection($this->box_sales)
        ];
    }
}
