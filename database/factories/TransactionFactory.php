<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition()
    {
        $total = $this->faker->randomElement([25000, 50000, 75000]);

        return [
            'code' => 'TRX-'.$this->faker->unique()->numerify('########'),
            'user_id' => User::factory(),
            'total' => $total,
            'payment_method' => $this->faker->randomElement(['cash', 'qris']),
            'paid_amount' => $total,
            'change_amount' => 0,
        ];
    }
}
