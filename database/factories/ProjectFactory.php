<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->domainWord(),
            'description' => $this->faker->randomElement([null, $this->faker->text()]),
            'pay_per_hour' => $this->faker->randomElement([null, $this->faker->numerify('###000')])
        ];
    }

    public function fulfil(): Factory
    {
        return $this->state(fn(array $attributes) => [
            'description' => $this->faker->text(),
            'pay_per_hour' => $this->faker->numerify('###000'),
        ]);
    }
}
