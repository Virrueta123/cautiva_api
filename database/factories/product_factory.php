<?php

namespace Database\Factories;

use App\Models\category;
use App\Models\modell;
use App\Models\product;
use App\Models\size;
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

        static $usedNames = []; // Almacena los nombres ya utilizados

        $model = modell::inRandomOrder()->first();
        $category = category::inRandomOrder()->first();
        $size = size::inRandomOrder()->first();
    
        if (!$model || !$category || !$size) {
            throw new \Exception("No se encontraron modelos o categorías disponibles");
        }
    
        $productNames = [
            "Blusa azul veraniega",
            "Vestido rojo de gala",
            "Falda larga floreada",
            "Camiseta blanca casual",
            "Blazer negro elegante",
            "Sudadera rosa deportiva",
            "Jeans ajustados azul oscuro",
            "Leggings negros de algodón",
            "Shorts vaqueros desgastados",
            "Blusa amarilla de lino",
            "Pantalón de vestir gris",
            "Chaleco beige acolchado",
            "Abrigo largo camel",
            "Suéter verde tejido",
            "Camisón blanco de encaje",
            "Traje de baño rojo entero",
            "Pijama de seda morada",
            "Top lila sin mangas",
            "Chaqueta marrón de cuero",
            "Pantalón capri blanco",
            "Kimono estampado floral",
            "Camisa vaquera azul",
            "Blusa negra con volantes",
            "Falda de cuero negra",
            "Suéter crema con cuello alto",
            "Abrigo de piel sintética",
            "Cardigan gris oversize",
            "Mono negro elegante",
            "Camiseta de rayas marineras",
            "Blusa coral con escote V",
            "Vestido azul plisado",
            "Pantalón jogger gris",
            "Falda midi dorada",
            "Top blanco de encaje",
            "Traje de baño bikini tropical",
            "Pantalón palazzo rojo",
            "Blusa verde oliva de seda",
            "Chaqueta impermeable amarilla",
            "Camiseta básica negra",
            "Vestido beige de punto",
            "Falda mini de lentejuelas",
            "Sudadera con capucha gris",
            "Abrigo azul marino cruzado",
            "Leggings estampados geométricos",
            "Blusa fucsia de tirantes",
            "Vestido blanco estilo bohemio",
            "Pantalón culotte marrón",
            "Falda lápiz gris",
            "Camisa de cuadros rosa",
            "Top negro de tirantes",
            "Parka verde militar",
            "Camiseta estampada floral",
            "Blusa azul celeste bordada",
            "Vestido lila cruzado",
            "Chaqueta denim oversized",
            "Pantalón cargo beige",
            "Falda plisada rosada",
            "Top azul cielo asimétrico",
            "Chaleco blanco de lana",
            "Blusa estampada tropical",
            "Sudadera negra con letras",
            "Abrigo gris de lana",
            "Mono blanco con cinturón",
            "Vestido amarillo con volantes",
            "Leggings morados deportivos",
            "Blusa turquesa sin mangas",
            "Camiseta manga larga gris",
            "Pantalón pitillo negro",
            "Falda de tul rosa pastel",
            "Top rojo con hombros descubiertos",
            "Blazer blanco cruzado",
            "Vestido naranja ajustado",
            "Chaqueta bomber negra",
            "Blusa beige con encaje",
            "Jeans rotos azul claro",
            "Pantalón ancho a rayas",
            "Sudadera beige con capucha",
            "Falda verde plisada",
            "Top gris deportivo",
            "Blusa violeta con lazo",
            "Camiseta amarilla básica",
            "Vestido azul de lunares",
            "Pantalón de pana marrón",
            "Falda con botones delanteros",
            "Top bordado estilo mexicano",
            "Chaleco gris de punto",
            "Blusa blanca con transparencias",
            "Vestido verde con estampado floral",
            "Leggings térmicos negros",
            "Camisa amarilla con estampado",
            "Falda corta de mezclilla",
            "Sudadera azul oversize",
            "Abrigo rojo largo",
            "Blusa negra satinada",
            "Vestido fucsia ajustado",
            "Top nude con tirantes finos",
            "Pantalón deportivo azul marino",
            "Falda cruzada estampada",
            "Blazer gris de oficina"
        ];
    
        // Filtrar los nombres ya utilizados
        $availableNames = array_diff($productNames, $usedNames);
    
        if (empty($availableNames)) {
            throw new \Exception("No hay más nombres únicos disponibles");
        }
    
        // Elegir un nombre único
        $productName = $this->faker->randomElement($availableNames);
    
        // Marcar el nombre como utilizado
        $usedNames[] = $productName;
    
        $porcentage_ganancia = $this->faker->randomElement([25, 40, 50]);
        $purchase_price = $this->faker->randomFloat(2, 10, 50);
        $price_ganancia = $purchase_price * ($porcentage_ganancia / 100);
        $sales_price = $purchase_price + $price_ganancia;
        $product_profit = $sales_price - $purchase_price;
    
        return [
            'product_name' => $productName,
            'product_purchase' => strval($purchase_price),
            'product_profit' => strval($product_profit),
            'product_sales' => strval($sales_price),
            'product_stock' => $this->faker->numberBetween(1, 500),
            'category_id' => $category->category_id,
            'model_id' => $model->model_id,
            'size_id' => $size->size_id,
            'created_by' => 1,
        ];
    }
}
