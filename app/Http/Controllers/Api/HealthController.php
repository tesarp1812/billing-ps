<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\ApiResponse;

class HealthController extends Controller
{
    public function __invoke()
    {
        return ApiResponse::success('API is healthy.', [
            'status' => 'ok',
            'timestamp' => now()->toIso8601String(),
        ]);
    }
}
