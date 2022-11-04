<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class TimeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'start_at' => $this->faker->dateTime,
            'end_at' => null
        ];
    }

    public function fulfil(): Factory
    {
        return $this->state(fn(array $attributes) => [
            'end_at' => $this->faker->dateTime
        ]);
    }
}
