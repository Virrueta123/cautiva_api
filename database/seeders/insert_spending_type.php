<?php

namespace Database\Seeders;

use App\Models\spending_type;
use App\Models\user;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class insert_spending_type extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        // seleccionar el primer usuario de todo los usuarios 
        $first_user =  user::all()->first();
        $timestamp = ["created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s")];

        // add spendings type
        $list_spending_type = [
            ['spending_type_name' => 'Gastos de compra', 'created_by' => $first_user->id],
            ['spending_type_name' => 'Gastos de venta', 'created_by' => $first_user->id],
            ['spending_type_name' => 'Gastos de servicio', 'created_by' => $first_user->id],
            ['spending_type_name' => 'Gastos de reparacion', 'created_by' => $first_user->id],
            ['spending_type_name' => 'Gastos de mantenimiento', 'created_by' => $first_user->id],
            ['spending_type_name' => 'Gastos de transporte', 'created_by' => $first_user->id],
            ['spending_type_name' => 'Gastos de pago', 'created_by' => $first_user->id],
            ['spending_type_name' => 'Otros gastos', 'created_by' => $first_user->id],
        ];
 

        $list_spending_type = array_map(function ($spending_type) use ($timestamp) {
            return array_merge($spending_type, $timestamp);
        }, $list_spending_type);

        spending_type::insert($list_spending_type);
    }
}
