<?php

namespace App\Services\Reports;

use App\Models\StationSession;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class DailyReportService
{
    public function daily(string $date)
    {
        $hourExpression = $this->hourExpression();
        $dateExpression = $this->dateExpression();

        $revenue = (float) Transaction::query()
            ->whereDate('created_at', $date)
            ->sum('total');

        $transactions = Transaction::query()
            ->whereDate('created_at', $date)
            ->count();

        $busyHours = StationSession::query()
            ->selectRaw($hourExpression.' as hour, COUNT(*) as total_sessions, SUM(subtotal) as total_revenue')
            ->whereDate('created_at', $date)
            ->groupBy(DB::raw($hourExpression))
            ->orderBy('hour')
            ->get()
            ->map(function ($item) {
                return [
                    'hour' => sprintf('%02d:00', $item->hour),
                    'total_sessions' => (int) $item->total_sessions,
                    'total_revenue' => (float) $item->total_revenue,
                ];
            })
            ->values();

        $sevenDays = Transaction::query()
            ->selectRaw($dateExpression.' as date, SUM(total) as total')
            ->whereDate('created_at', '>=', now()->subDays(6)->toDateString())
            ->groupBy(DB::raw($dateExpression))
            ->orderBy('date')
            ->get()
            ->map(function ($item) {
                return [
                    'date' => $item->date,
                    'total' => (float) $item->total,
                ];
            })
            ->values();

        return [
            'date' => $date,
            'revenue' => $revenue,
            'transactions' => $transactions,
            'average_transaction' => $transactions > 0 ? round($revenue / $transactions, 2) : 0,
            'busy_hours' => $busyHours,
            'seven_day_revenue' => $sevenDays,
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

    protected function dateExpression(): string
    {
        return match (DB::connection()->getDriverName()) {
            'sqlite' => 'date(created_at)',
            default => 'DATE(created_at)',
        };
    }
}
