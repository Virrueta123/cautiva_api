<?php

namespace Database\Factories;

use App\Models\client;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class client_factory extends Factory
{
    protected $model = client::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $hasRUC = $this->faker->boolean(70); // 70% de probabilidad de tener RUC

        return [
            'name' => $this->faker->firstName(),
            'lastname' => $this->faker->lastName(),
            'dni' => $this->faker->numerify('########'), // Genera un número de 8 dígitos
            'address' => $this->faker->streetAddress(), 
            'ruc' => $hasRUC ? $this->faker->numerify('###########') : null, // Si no tiene RUC, será NULL
            'bussiness_name' => $hasRUC ? $this->faker->company() : null, // Solo si tiene RUC, tendrá nombre de negocio
            'phone' => $this->faker->numerify('9########'), // Número de teléfono
            'created_by' => 1,  
        ];
    }
}
