<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Dashboard\DashboardService;

class DashboardSummaryController extends Controller
{
    public function __invoke(DashboardService $dashboardService)
    {
        return response()->json($dashboardService->summary());
    }
}
