<?php

namespace Database\Factories;

use App\Models\Producto;
use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    protected $model = Producto::class;

    public function definition()
    {
        return [
            'nombre'       => $this->faker->word(),
            'marca'        => $this->faker->company(),
            'codigo'       => $this->faker->unique()->bothify('PROD-#####'),
            'lineas'       => $this->faker->numberBetween(1, 10),
            'categoria_id' => Categoria::query()->inRandomOrder()->value('id'),
        ];
    }

    /**
     * Relación con presentaciones, capacidades y unidades
     */
    public function withRelaciones()
    {
        return $this->afterCreating(function (Producto $producto) {
            $producto->presentaciones()->attach(
                \App\Models\Presentacion::inRandomOrder()->take(rand(1, 3))->pluck('id')
            );

            $producto->capacidades()->attach(
                \App\Models\Capacidad::inRandomOrder()->take(rand(1, 2))->pluck('id')
            );

            $producto->unidades()->attach(
                \App\Models\Unidad::inRandomOrder()->take(rand(1, 2))->pluck('id')
            );
        });
    }
}