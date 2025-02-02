<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountryFactory extends Factory
{
    protected $model = Country::class;

    public function definition()
    {
        return [
            'code' => $this->faker->unique()->randomElement(['DE', 'IT', 'GR']),
            'name' => $this->faker->country,
            'tax_rate' => $this->faker->numberBetween(19, 24),
        ];
    }
}