<?php

namespace App\Http\Resources\box;

use App\Utils\encryptor;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class box_show_resource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Cache sums to avoid redundant calculations
        $spendingTotal = $this->spending_account->sum('amount');
        $salesTotal = $this->sales_amount->sum('amount');
        $saleCashTotal = $this->sale_account(true)->sum('amount'); 
        $saleAccountTotal = $this->sale_account(false)->sum('amount');
        $spendingCashTotal = $this->spending_account(true)->sum('amount');
        $spendingAccountTotal = $this->spending_account(false)->sum('amount');

        return [
            // Encrypted unique identifier
            'identifier' => encryptor::encrypt($this->box_id),

            // Box name
            'name' => $this->name,

            // Box reference
            'reference' => $this->reference,

            // Total spending amount as a string
            'spending_amount' => strval($spendingTotal),

            // Total sales amount as a string
            'sales_amount' => strval($salesTotal),

            // Initial balance as a string
            'initial_balance' => strval($this->initial_balance),

            // Calculated box amount (sales - spending) as a string
            'box_amount' => strval($salesTotal - $spendingTotal),

            // Final balance based on status
             'final_balance' => strval(
                $this->status == "C"
                    ? $this->final_balance
                    : $this->sales_amount->sum('amount') - $this->spending_amount->sum('amount') + $this->initial_balance
            ),

            // Total sales by payment type (cash and account) as strings
            'sale_payment_cash' => strval($saleCashTotal),
            'sale_payment_account' => strval($saleAccountTotal),

            // Total spending by payment type (cash and account) as strings
            'spending_payment_cash' => strval($spendingCashTotal),
            'spending_payment_account' => strval($spendingAccountTotal),

            // Final cash balance as a string
            'final_balance_cash' => strval($saleCashTotal - $spendingCashTotal + $this->initial_balance),

            // Final account balance as a string
            'final_balance_account' => strval($saleAccountTotal - $spendingAccountTotal),

            // Box-related dates
            'opening_date' => $this->opening_date,
            'closing_date' => $this->closing_date,

            // Current box status
            'status' => $this->status
        ];
    }
}
