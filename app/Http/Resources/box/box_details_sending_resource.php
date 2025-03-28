<?php

namespace App\Http\Resources\box;

use App\Utils\encryptor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class box_details_sending_resource extends JsonResource
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
        // Cache sums to avoid redundant calculations
        $spendingTotal = $this->spending_amount->sum('amount');
        $spendingCashTotal = $this->spending_account(true)->sum('amount');
        $spendingAccountTotal = $this->spending_account(false)->sum('amount');
        return [
            'identifier' => encryptor::encrypt($this->box_id),
            'reference' => $this->reference,
            'name' => $this->name,
            // Total spending amount as a string
            'spending_amount' => strval($spendingTotal),
            // Total spending by payment type (cash and account) as strings
            'spending_payment_cash' => strval($spendingCashTotal),
            'spending_payment_account' => strval($spendingAccountTotal),
            'sale_account' => $this->paymentAccountGeneral,
        ];
    }
}
