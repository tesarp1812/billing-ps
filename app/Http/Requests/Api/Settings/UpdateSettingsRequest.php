<?php

namespace App\Http\Requests\Api\Settings;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'business_name' => ['nullable', 'string', 'max:100'],
            'business_address' => ['nullable', 'string', 'max:255'],
            'business_whatsapp' => ['nullable', 'string', 'max:20'],
            'business_logo' => ['nullable', 'string'],
            'theme_color' => ['nullable', 'string', 'max:20'],
            'theme_mode' => ['nullable', 'string', 'in:dark,light'],
            'price_regular' => ['nullable', 'numeric', 'min:0'],
            'price_vip' => ['nullable', 'numeric', 'min:0'],
            'grace_period' => ['nullable', 'integer', 'min:0', 'max:60'],
            'auto_round' => ['nullable', 'boolean'],
            'minimum_charge' => ['nullable', 'numeric', 'min:0'],
            'open_time' => ['nullable', 'date_format:H:i'],
            'close_time' => ['nullable', 'date_format:H:i'],
            'idle_logout_minutes' => ['nullable', 'integer', 'min:1'],
            'default_printer' => ['nullable', 'string', 'max:100'],
            'timezone' => ['nullable', 'string', 'max:50'],
            'currency' => ['nullable', 'string', 'max:10'],
            'tax_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'service_charge_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }
}
