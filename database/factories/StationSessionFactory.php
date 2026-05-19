<?php

namespace Database\Factories;

use App\Models\Station;
use App\Models\StationSession;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StationSessionFactory extends Factory
{
    protected $model = StationSession::class;

    public function definition()
    {
        $start = now()->subMinutes($this->faker->numberBetween(15, 180));

        return [
            'station_id' => Station::factory(),
            'user_id' => User::factory(),
            'customer_name' => $this->faker->firstName(),
            'start_time' => $start,
            'end_time' => null,
            'duration_minutes' => 0,
            'subtotal' => 0,
            'status' => 'active',
        ];
    }
}
