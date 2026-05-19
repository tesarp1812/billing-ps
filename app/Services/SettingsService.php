<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    protected array $defaults = [
        'business_name' => ['value' => 'PS Billing POS', 'type' => 'string'],
        'business_address' => ['value' => '', 'type' => 'string'],
        'business_whatsapp' => ['value' => '', 'type' => 'string'],
        'business_logo' => ['value' => null, 'type' => 'image'],
        
        'theme_color' => ['value' => '#06b6d4', 'type' => 'string'],
        'theme_mode' => ['value' => 'dark', 'type' => 'string'],
        
        'price_regular' => ['value' => 10000, 'type' => 'number'],
        'price_vip' => ['value' => 15000, 'type' => 'number'],
        'grace_period' => ['value' => 5, 'type' => 'number'],
        'auto_round' => ['value' => true, 'type' => 'boolean'],
        'minimum_charge' => ['value' => 5000, 'type' => 'number'],
        
        'open_time' => ['value' => '10:00', 'type' => 'string'],
        'close_time' => ['value' => '23:00', 'type' => 'string'],
        'idle_logout_minutes' => ['value' => 30, 'type' => 'number'],
        'default_printer' => ['value' => '', 'type' => 'string'],
        
        'timezone' => ['value' => 'Asia/Jakarta', 'type' => 'string'],
        'currency' => ['value' => 'IDR', 'type' => 'string'],
        'tax_percent' => ['value' => 0, 'type' => 'number'],
        'service_charge_percent' => ['value' => 0, 'type' => 'number'],
    ];

    public function all(): array
    {
        $settings = Setting::all()->keyBy('key');
        $result = [];

        foreach ($this->defaults as $key => $default) {
            $setting = $settings->get($key);
            $result[$key] = $setting ? $this->castValue($setting->value, $default['type']) : $default['value'];
        }

        return $result;
    }

    public function get(string $key, $default = null)
    {
        $setting = Setting::where('key', $key)->first();
        
        if (!$setting) {
            return $this->defaults[$key]['value'] ?? $default;
        }

        return $this->castValue($setting->value, $this->defaults[$key]['type'] ?? 'string');
    }

    public function set(array $settings): array
    {
        foreach ($settings as $key => $value) {
            if (!isset($this->defaults[$key])) {
                continue;
            }

            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $this->encodeValue($value),
                    'type' => $this->defaults[$key]['type'],
                ]
            );
        }

        Cache::forget('settings');

        return $this->all();
    }

    public function reset(): array
    {
        Setting::whereIn('key', array_keys($this->defaults))->delete();
        
        return $this->all();
    }

    protected function castValue($value, string $type)
    {
        if ($value === null) {
            return null;
        }

        return match ($type) {
            'number' => (float) $value,
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'json' => json_decode($value, true),
            default => $value,
        };
    }

    protected function encodeValue($value): string
    {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_array($value)) {
            return json_encode($value);
        }

        return (string) $value;
    }
}