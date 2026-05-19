<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionItemFactory extends Factory
{
    protected $model = TransactionItem::class;

    public function definition()
    {
        return [
            'transaction_id' => Transaction::factory(),
            'item_type' => 'product',
            'ref_id' => null,
            'name' => $this->faker->words(2, true),
            'qty' => 1,
            'price' => 10000,
            'subtotal' => 10000,
        ];
    }
}
