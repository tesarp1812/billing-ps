<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    protected $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    public function index()
    {
        return response()->json([
            'settings' => $this->settingsService->all(),
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'business_name' => 'nullable|string|max:100',
            'business_address' => 'nullable|string|max:255',
            'business_whatsapp' => 'nullable|string|max:20',
            'business_logo' => 'nullable|string',
            'theme_color' => 'nullable|string|max:20',
            'theme_mode' => 'nullable|string|in:dark,light',
            'price_regular' => 'nullable|numeric|min:0',
            'price_vip' => 'nullable|numeric|min:0',
            'grace_period' => 'nullable|integer|min:0|max:60',
            'auto_round' => 'nullable|boolean',
            'minimum_charge' => 'nullable|numeric|min:0',
            'open_time' => 'nullable|date_format:H:i',
            'close_time' => 'nullable|date_format:H:i',
            'idle_logout_minutes' => 'nullable|integer|min:1',
            'default_printer' => 'nullable|string|max:100',
            'timezone' => 'nullable|string|max:50',
            'currency' => 'nullable|string|max:10',
            'tax_percent' => 'nullable|numeric|min:0|max:100',
            'service_charge_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $settings = $this->settingsService->set($request->all());

        return response()->json([
            'message' => 'Pengaturan berhasil disimpan.',
            'settings' => $settings,
        ]);
    }

    public function reset()
    {
        $settings = $this->settingsService->reset();

        return response()->json([
            'message' => 'Pengaturan direset ke default.',
            'settings' => $settings,
        ]);
    }
}