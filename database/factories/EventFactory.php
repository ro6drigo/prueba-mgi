<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        return [
            'container_id' => $this->faker->uuid(),
            'state' => $this->faker->randomElement(['operational', 'damaged', 'unknown']),
            'source' => $this->faker->word(),
            'timestamp' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
