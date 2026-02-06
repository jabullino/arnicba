<?php

namespace Database\Factories;

use App\Models\Vestimenta;
use App\Models\Producto;
use App\Models\Talla;
use App\Models\Color;
use Illuminate\Database\Eloquent\Factories\Factory;

class VestimentaFactory extends Factory
{
    protected $model = Vestimenta::class;

    public function definition()
    {
        return [
            'producto_id' => Producto::factory()->withRelaciones(),
            'talla_id'    => Talla::inRandomOrder()->first()->id,
            'color_id'    => Color::inRandomOrder()->first()->id,
        ];
    }
}