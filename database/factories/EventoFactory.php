<?php

namespace Database\Factories;

use App\Models\Evento;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventoFactory extends Factory
{
    protected $model = Evento::class;

    public function definition()
    {
        return [
			'nome' => $this->faker->name,
			'descricao' => $this->faker->name,
			'data_inicio' => $this->faker->name,
			'data_fim' => $this->faker->name,
			'link' => $this->faker->name,
			'imagem' => $this->faker->name,
			'is_active' => $this->faker->name,
        ];
    }
}
