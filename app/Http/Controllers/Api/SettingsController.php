<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Settings\UpdateSettingsRequest;
use App\Services\SettingsService;
use App\Support\ApiResponse;

class SettingsController extends Controller
{
    protected $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    public function index()
    {
        return ApiResponse::success('Pengaturan berhasil diambil.', [
            'settings' => $this->settingsService->all(),
        ]);
    }

    public function update(UpdateSettingsRequest $request)
    {
        $settings = $this->settingsService->set($request->validated());

        return ApiResponse::success('Pengaturan berhasil disimpan.', [
            'settings' => $settings,
        ]);
    }

    public function reset()
    {
        $settings = $this->settingsService->reset();

        return ApiResponse::success('Pengaturan direset ke default.', [
            'settings' => $settings,
        ]);
    }
}
