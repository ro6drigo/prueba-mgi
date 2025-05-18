<?php

namespace Database\Factories;

use App\Models\Container;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContainerFactory extends Factory
{
    protected $model = Container::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid(),
            'state' => 'unknown',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
