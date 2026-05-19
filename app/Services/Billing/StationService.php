<?php

namespace App\Services\Billing;

use App\Models\Station;
use App\Models\StationSession;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class StationService
{
    public function list()
    {
        return Station::query()
            ->where('is_active', true)
            ->orderBy('type')
            ->orderBy('name')
            ->get()
            ->map(function (Station $station) {
                $session = $this->latestUnpaidSession($station);

                return $this->transformStation($station, $session);
            });
    }

    public function start(Station $station, ?string $customerName, int $userId, int $packageMinutes = 0)
    {
        $session = $this->latestUnpaidSession($station);

        if ($station->status === 'playing') {
            throw ValidationException::withMessages([
                'station' => 'Station sedang berjalan.',
            ]);
        }

        if ($session && $session->status === 'finished') {
            throw ValidationException::withMessages([
                'station' => 'Station sudah dihentikan dan menunggu checkout.',
            ]);
        }

        $billingType = $packageMinutes > 0 ? 'package' : 'free';
        $packageEndTime = $packageMinutes > 0 ? now()->addMinutes($packageMinutes) : null;

        if ($session && $station->status === 'paused') {
            $session->update([
                'start_time' => now(),
                'status' => 'active',
                'customer_name' => $customerName ?: $session->customer_name,
            ]);
        } else {
            $session = StationSession::create([
                'station_id' => $station->id,
                'user_id' => $userId,
                'customer_name' => $customerName,
                'start_time' => now(),
                'duration_minutes' => 0,
                'subtotal' => 0,
                'status' => 'active',
                'billing_type' => $billingType,
                'package_minutes' => $packageMinutes,
                'package_end_time' => $packageEndTime,
            ]);
        }

        $station->update(['status' => 'playing']);

        return $this->transformStation($station->fresh(), $session->fresh());
    }

    public function pause(Station $station)
    {
        $session = $this->requireActiveSession($station);
        $minutes = $session->duration_minutes + $this->calculateRunningMinutes($session);

        $session->update([
            'duration_minutes' => $minutes,
            'subtotal' => $this->calculateSubtotal($station, $minutes),
            'start_time' => now(),
        ]);

        $station->update(['status' => 'paused']);

        return $this->transformStation($station->fresh(), $session->fresh());
    }

    public function stop(Station $station)
    {
        $session = $this->latestUnpaidSession($station);

        if (! $session) {
            throw ValidationException::withMessages([
                'station' => 'Belum ada sesi aktif untuk station ini.',
            ]);
        }

        $minutes = $session->duration_minutes;

        if ($station->status === 'playing' && $session->status === 'active') {
            $minutes += $this->calculateRunningMinutes($session);
        }

        $session->update([
            'end_time' => now(),
            'duration_minutes' => $minutes,
            'subtotal' => $this->calculateSubtotal($station, $minutes),
            'status' => 'finished',
        ]);

        $station->update(['status' => 'booking']);

        return $this->transformStation($station->fresh(), $session->fresh());
    }

    public function addTime(Station $station, int $minutes)
    {
        $session = $this->latestUnpaidSession($station);

        if (! $session) {
            throw ValidationException::withMessages([
                'station' => 'Belum ada sesi untuk ditambahkan waktu.',
            ]);
        }

        if ($session->billing_type === 'package' && $session->package_minutes > 0) {
            $newPackageMinutes = $session->package_minutes + $minutes;
            
            $session->update([
                'package_minutes' => $newPackageMinutes,
                'package_end_time' => $session->start_time->addMinutes($newPackageMinutes),
            ]);
        } else {
            $newDuration = $session->duration_minutes + $minutes;

            $session->update([
                'duration_minutes' => $newDuration,
                'subtotal' => $this->calculateSubtotal($station, $newDuration),
            ]);
        }

        return $this->transformStation($station->fresh(), $session->fresh());
    }

    public function latestUnpaidSession(Station $station)
    {
        return StationSession::query()
            ->where('station_id', $station->id)
            ->whereNotExists(function ($query) {
                $query->selectRaw('1')
                    ->from('transaction_items')
                    ->whereColumn('transaction_items.ref_id', 'sessions.id')
                    ->where('transaction_items.item_type', 'session');
            })
            ->latest('id')
            ->first();
    }

    public function transformStation(Station $station, ?StationSession $session)
    {
        $elapsedMinutes = 0;
        $remainingMinutes = 0;
        $isPackageExpired = false;

        if ($session) {
            $elapsedMinutes = $session->duration_minutes;

            if ($station->status === 'playing' && $session->status === 'active') {
                $elapsedMinutes += $this->calculateRunningMinutes($session);
            }

            if ($session->billing_type === 'package' && $session->package_minutes > 0) {
                $remainingMinutes = max(0, $session->package_minutes - $elapsedMinutes);
                
                if ($remainingMinutes <= 0 && $station->status === 'playing') {
                    $isPackageExpired = true;
                }
            }
        }

        return [
            'id' => $station->id,
            'name' => $station->name,
            'code' => $station->code,
            'type' => $station->type,
            'price_per_hour' => (float) $station->price_per_hour,
            'status' => $station->status,
            'is_active' => $station->is_active,
            'current_session' => $session ? [
                'id' => $session->id,
                'customer_name' => $session->customer_name,
                'start_time' => optional($session->start_time)->toIso8601String(),
                'end_time' => optional($session->end_time)->toIso8601String(),
                'duration_minutes' => (int) $session->duration_minutes,
                'elapsed_minutes' => $elapsedMinutes,
                'remaining_minutes' => $remainingMinutes,
                'subtotal' => (float) $this->calculateSubtotal($station, $elapsedMinutes),
                'status' => $session->status,
                'billing_type' => $session->billing_type ?? 'free',
                'package_minutes' => (int) $session->package_minutes,
                'package_end_time' => optional($session->package_end_time)->toIso8601String(),
                'is_expired' => $isPackageExpired,
            ] : null,
        ];
    }

    protected function requireActiveSession(Station $station)
    {
        $session = $this->latestUnpaidSession($station);

        if (! $session || $station->status !== 'playing' || $session->status !== 'active') {
            throw ValidationException::withMessages([
                'station' => 'Station tidak sedang bermain.',
            ]);
        }

        return $session;
    }

    protected function calculateRunningMinutes(StationSession $session)
    {
        return max(1, Carbon::parse($session->start_time)->diffInMinutes(now()));
    }

    public function calculateSubtotal(Station $station, int $minutes)
    {
        $perMinute = ((float) $station->price_per_hour) / 60;

        return round($perMinute * max(0, $minutes), 2);
    }
}
