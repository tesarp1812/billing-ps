<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Reports\DailyReportRequest;
use App\Services\Reports\DailyReportService;

class ReportController extends Controller
{
    public function daily(DailyReportRequest $request, DailyReportService $dailyReportService)
    {
        $date = $request->input('date', now()->toDateString());

        return response()->json($dailyReportService->daily($date));
    }
}
