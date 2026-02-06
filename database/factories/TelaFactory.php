<?php

namespace Database\Factories;

use App\Models\Tela;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Color;

class TelaFactory extends Factory
{
    protected $model = Tela::class;

    public function definition()
    {
        return [
            'producto_id' => Producto::factory(),
            'color_id'    => Color::query()->inRandomOrder()->value('id'),
            'ancho'       => fake()->randomFloat(2, 1, 5),
            'largo'       => fake()->randomFloat(2, 1, 20),
        ];
    }
}
