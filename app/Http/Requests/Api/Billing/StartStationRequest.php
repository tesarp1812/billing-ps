<?php

namespace App\Http\Requests\Api\Billing;

use Illuminate\Foundation\Http\FormRequest;

class StartStationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'customer_name' => 'nullable|string|max:100',
            'package_minutes' => 'nullable|integer|min:0',
        ];
    }
}
