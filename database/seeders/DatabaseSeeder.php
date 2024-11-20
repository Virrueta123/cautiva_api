<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\category;
use App\Models\modell; 
use Carbon\Carbon;
use Database\Factories\user_factory;
use Illuminate\Database\Seeder;

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

        $first_user =  user_factory::new()->count(1)->create()->first(); 

        $timestamp = ["created_at" => Carbon::now()->format("Y-m-d H:i:s"), "updated_at" => Carbon::now()->format("Y-m-d H:i:s")];

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

        $list_models = [
            ['model_name' => 'Ropa para Niños','created_by' => $first_user->id],
            ['model_name' => 'Ropa para Adultos','created_by' => $first_user->id ], 
            ['model_name' => 'Ropa para Jóvenes','created_by' => $first_user->id ], 
            ['model_name' => 'Ropa para Mujeres','created_by' => $first_user->id ]
        ];

         // Agregar timestamps a cada registro
         $list_models = array_map(function ($category) use ($timestamp) {
            return array_merge($category, $timestamp);
        }, $list_models);

        category::insert($list_categories);
        modell::insert($list_models);
    }
}
