<?php

namespace Database\Factories;

use App\Models\Station;
use Illuminate\Database\Eloquent\Factories\Factory;

class StationFactory extends Factory
{
    protected $model = Station::class;

    public function definition()
    {
        return [
            'name' => 'PS '.$this->faker->unique()->numberBetween(1, 20),
            'code' => strtoupper($this->faker->unique()->bothify('ST-###')),
            'type' => $this->faker->randomElement(['regular', 'vip']),
            'price_per_hour' => $this->faker->randomElement([12000, 15000, 20000]),
            'status' => 'empty',
            'is_active' => true,
        ];
    }
}
