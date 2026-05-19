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
        $hourExpression = $this->hourExpression();

        $todayRevenue = (float) Transaction::query()
            ->whereDate('created_at', now()->toDateString())
            ->sum('total');

        $busyHours = Transaction::query()
            ->selectRaw($hourExpression.' as hour, SUM(total) as total')
            ->whereDate('created_at', now()->toDateString())
            ->groupBy(DB::raw($hourExpression))
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

    protected function hourExpression(): string
    {
        return match (DB::connection()->getDriverName()) {
            'pgsql' => 'EXTRACT(HOUR FROM created_at)',
            'sqlite' => "CAST(strftime('%H', created_at) AS INTEGER)",
            default => 'HOUR(created_at)',
        };
    }
}
