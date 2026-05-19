<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\DashboardService;
use App\Support\ApiResponse;

class DashboardSummaryController extends Controller
{
    public function __invoke(DashboardService $dashboardService)
    {
        return ApiResponse::success('Ringkasan dashboard berhasil diambil.', $dashboardService->summary());
    }
}
