<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\account;
use App\Models\category;
use App\Models\client;
use App\Models\config;
use App\Models\modell;
use App\Models\size;
use Carbon\Carbon;
use Database\Factories\product_factory;
use Database\Factories\user_factory;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */



    public function run(): void
    {

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // registro establecidos

        // $first_user =  user_factory::new()->count(1)->create()->first();

        $first_user = User::create([
            'name' => 'Olga',
            'username' => 'olga',
            'password' => Hash::make('olga123456'),
            'type_user' => 'admin',
            'remember_token' => Str::random(10),
        ]);

        // Crear recepcionista
        User::create([
            'name' => 'lorena',
            'username' => 'lorena',
            'password' => Hash::make('lorena123456'),
            'type_user' => 'recepcionista',
            'remember_token' => Str::random(10),
        ]);

        // Crear vendedores
        User::create([
            'name' => 'Alejandro',
            'username' => 'alejandro',
            'password' => Hash::make('alejandro123456'),
            'type_user' => 'vendedor',
            'remember_token' => Str::random(10),
        ]);

        User::create([
            'name' => 'Rafael',
            'username' => 'rafael',
            'password' => Hash::make('rafael123456'),
            'type_user' => 'vendedor',
            'remember_token' => Str::random(10),
        ]);

        $timestamp = ["created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s")];


        //created categories
        $list_categories = [
            ['category_name' => 'Deportivo', 'category_description' => "Ropa diseñada para hacer ejercicio", 'created_by' => $first_user->id],
            ['category_name' => 'Ropa de verano', 'category_description' => "Ropa ligera y fresca para climas cálido", 'created_by' => $first_user->id],
            ['category_name' => 'Estilo Urbano', 'category_description' => "Ropa influenciada por la cultura urbana", 'created_by' => $first_user->id],
            ['category_name' => 'Vintage', 'category_description' => "Ropa de épocas pasadas", 'created_by' => $first_user->id],
        ];


        // Agregar timestamps a cada registro
        $list_categories = array_map(function ($category) use ($timestamp) {
            return array_merge($category, $timestamp);
        }, $list_categories);


        //create size
        $list_sizes = [
            ['size_name' => 'XL',  'created_by' => $first_user->id],
            ['size_name' => 'L', 'created_by' => $first_user->id],
            ['size_name' => 'M',  'created_by' => $first_user->id],
            ['size_name' => 'S',  'created_by' => $first_user->id],
            ['size_name' => 'XS',  'created_by' => $first_user->id],
        ];
        // Agregar timestamps a cada registro
        $list_sizes = array_map(function ($size) use ($timestamp) {
            return array_merge($size, $timestamp);
        }, $list_sizes);

        size::insert($list_sizes);


        $list_models = [
            ['model_name' => 'Ropa para Niños', 'created_by' => $first_user->id],
            ['model_name' => 'Ropa para Adultos', 'created_by' => $first_user->id],
            ['model_name' => 'Ropa para Jóvenes', 'created_by' => $first_user->id],
            ['model_name' => 'Ropa para Mujeres', 'created_by' => $first_user->id]
        ];

        // Agregar timestamps a cada registro
        $list_models = array_map(function ($category) use ($timestamp) {
            return array_merge($category, $timestamp);
        }, $list_models);

        $list_accounts = [
            ['account_name' => 'Efectivo', 'created_by' => $first_user->id],
            ['account_name' => 'Yape', 'created_by' => $first_user->id],
            ['account_name' => 'Plin', 'created_by' => $first_user->id]
        ];

        $list_accounts = array_map(function ($accounts) use ($timestamp) {
            return array_merge($accounts, $timestamp);
        }, $list_accounts);

        //crear cliente varios
        $list_client = [
            'name' => 'Clientes',
            'lastname' => 'Varios',
            'dni' => '00000000',
            'is_default' => 'Y',
            'created_by' => $first_user->id
        ];

        $timestamp = [
            'created_at' => now(),
            'updated_at' => now()
        ];

        // Combinar arrays
        $list_client = array_merge($list_client, $timestamp);
        client::insert($list_client);
        //end


        //crear config 
        $list_config = [
            'series_invoice' => 'FOO1',
            'series_ticket' => 'B001',
            'series_note' => 'NV01',
        ];

        $timestamp = [
            'created_at' => now(),
            'updated_at' => now()
        ];

        // Combinar arrays
        $list_config = array_merge($list_config, $timestamp);
        config::insert($list_config);
       
        // create new sending type 

        category::insert($list_categories);
        modell::insert($list_models);
        account::insert($list_accounts);

        if (!app()->environment('production')) {
            // product_factory::new()->count(50)->create();
            // \Database\Factories\client_factory::new()->count(50)->create();
        }
    }
}
