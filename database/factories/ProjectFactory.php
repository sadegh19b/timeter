<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->domainWord(),
            'description' => null,
            'pay_per_hour' => null,
            'use_persian_datetime_in_statistic' => false
        ];
    }

    public function fulfil(): Factory
    {
        return $this->state(fn(array $attributes) => [
            'description' => $this->faker->text(),
            'pay_per_hour' => $this->faker->numerify('###000')
        ]);
    }

    public function usePersianDatetimeForStatistic(): Factory
    {
        return $this->state(fn(array $attributes) => [
            'use_persian_datetime_in_statistic' => true
        ]);
    }
}
