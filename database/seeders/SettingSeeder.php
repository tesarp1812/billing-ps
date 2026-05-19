<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            ['key' => 'business_name', 'value' => 'PS Billing POS', 'type' => 'string'],
            ['key' => 'currency', 'value' => 'IDR', 'type' => 'string'],
            ['key' => 'receipt_footer', 'value' => 'Terima kasih sudah bermain.', 'type' => 'string'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
