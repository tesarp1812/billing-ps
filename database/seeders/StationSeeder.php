<?php

namespace Database\Seeders;

use App\Models\Station;
use Illuminate\Database\Seeder;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stations = [
            ['name' => 'PS1', 'code' => 'PS1', 'type' => 'regular', 'price_per_hour' => 12000],
            ['name' => 'PS2', 'code' => 'PS2', 'type' => 'regular', 'price_per_hour' => 12000],
            ['name' => 'PS3', 'code' => 'PS3', 'type' => 'regular', 'price_per_hour' => 12000],
            ['name' => 'VIP1', 'code' => 'VIP1', 'type' => 'vip', 'price_per_hour' => 20000],
            ['name' => 'VIP2', 'code' => 'VIP2', 'type' => 'vip', 'price_per_hour' => 20000],
        ];

        foreach ($stations as $station) {
            Station::updateOrCreate(
                ['code' => $station['code']],
                array_merge($station, [
                    'status' => 'empty',
                    'is_active' => true,
                ])
            );
        }
    }
}
