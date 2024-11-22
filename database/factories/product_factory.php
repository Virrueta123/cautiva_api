<?php

namespace Database\Factories;

use App\Models\category;
use App\Models\modell;
use App\Models\product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class product_factory extends Factory
{
    protected $model = product::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $model = modell::inRandomOrder()->first();
        $category = category::inRandomOrder()->first();

        if (!$model || !$category) {
            throw new \Exception("No se encontraron modelos o categorías disponibles");
        }

        $porcentage_ganancia = $this->faker->randomElement([25, 40, 50]);
        $purchase_price = $this->faker->randomFloat(2, 10, 50);  
        $price_ganancia = $purchase_price * ($porcentage_ganancia / 100);
        $sales_price = $purchase_price + $price_ganancia;

        $product_profit = $sales_price - $purchase_price;

        return [
            'product_name' => $this->faker->words(3, true),
            'product_purchase' => strval($purchase_price),
            'product_profit' => strval($product_profit),
            'product_sales' => strval($sales_price),
            'product_stock' => $this->faker->numberBetween(1, 500),
            'category_id' => $category->category_id, // Cambia "category" por "category_id"
            'model_id' => $model->model_id,       // Cambia "model" por "model_id"
            'created_by' => 1,                // Cambia "user" por "user_id" si es una relación
        ];
    }
}
