<?php

namespace App\Services\Dashboard;

use App\Models\Station;
use App\Models\Transaction;
use App\Services\Billing\StationService;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    protected $stationService;

    public function __construct(StationService $stationService)
    {
        $this->stationService = $stationService;
    }

    public function summary()
    {
        $todayRevenue = (float) Transaction::query()
            ->whereDate('created_at', now()->toDateString())
            ->sum('total');

        $busyHours = Transaction::query()
            ->selectRaw('HOUR(created_at) as hour, SUM(total) as total')
            ->whereDate('created_at', now()->toDateString())
            ->groupBy(DB::raw('HOUR(created_at)'))
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'hour' => sprintf('%02d:00', $item->hour),
                    'total' => (float) $item->total,
                ];
            })
            ->values();

        return [
            'today_revenue' => $todayRevenue,
            'today_transactions' => Transaction::query()->whereDate('created_at', now()->toDateString())->count(),
            'active_stations' => Station::query()->whereIn('status', ['playing', 'paused', 'booking'])->count(),
            'empty_stations' => Station::query()->where('status', 'empty')->count(),
            'stations' => $this->stationService->list(),
            'busy_hours' => $busyHours,
        ];
    }
}
