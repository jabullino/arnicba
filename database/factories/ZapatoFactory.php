<?php

namespace Database\Factories;

use App\Models\Zapato;
use App\Models\Producto;
use App\Models\TallaZapato;
use App\Models\Color;
use Illuminate\Database\Eloquent\Factories\Factory;

class ZapatoFactory extends Factory
{
    protected $model = Zapato::class;

    public function definition()
    {
        return [
            'producto_id' => Producto::factory()->withRelaciones(),
            'talla_id'    => TallaZapato::inRandomOrder()->first()->id,
            'color_id'    => Color::inRandomOrder()->first()->id,
        ];
    }
}