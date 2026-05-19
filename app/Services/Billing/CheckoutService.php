<?php

namespace App\Services\Billing;

use App\Models\Product;
use App\Models\Station;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CheckoutService
{
    protected $stationService;

    public function __construct(StationService $stationService)
    {
        $this->stationService = $stationService;
    }

    public function handle(array $payload, int $userId)
    {
        return DB::transaction(function () use ($payload, $userId) {
            $transaction = Transaction::create([
                'code' => $this->generateCode(),
                'user_id' => $userId,
                'total' => 0,
                'payment_method' => $payload['payment_method'],
                'paid_amount' => $payload['paid_amount'],
                'change_amount' => 0,
            ]);

            $total = 0;
            $sessionData = null;

            if (! empty($payload['station_id'])) {
                $station = Station::findOrFail($payload['station_id']);
                $session = $this->stationService->latestUnpaidSession($station);

                if ($station->status === 'playing') {
                    $this->stationService->stop($station);
                    $station = $station->fresh();
                    $session = $this->stationService->latestUnpaidSession($station);
                }

                if ($session) {
                    $sessionSubtotal = (float) $session->subtotal;
                    $transaction->items()->create([
                        'item_type' => 'session',
                        'ref_id' => $session->id,
                        'name' => 'Billing '.$station->name,
                        'qty' => 1,
                        'price' => $sessionSubtotal,
                        'subtotal' => $sessionSubtotal,
                    ]);

                    $total += $sessionSubtotal;
                    $sessionData = $session;

                    $station->update(['status' => 'empty']);
                }
            }

            foreach ($payload['items'] ?? [] as $item) {
                $product = Product::query()
                    ->where('is_active', true)
                    ->findOrFail($item['product_id']);

                if ($product->stock < $item['qty']) {
                    throw ValidationException::withMessages([
                        'items' => 'Stok produk '.$product->name.' tidak mencukupi.',
                    ]);
                }

                $subtotal = (float) $product->price * $item['qty'];

                $transaction->items()->create([
                    'item_type' => 'product',
                    'ref_id' => $product->id,
                    'name' => $product->name,
                    'qty' => $item['qty'],
                    'price' => $product->price,
                    'subtotal' => $subtotal,
                ]);

                $product->decrement('stock', $item['qty']);
                $total += $subtotal;
            }

            if ($total <= 0) {
                throw ValidationException::withMessages([
                    'checkout' => 'Tidak ada item yang bisa diproses pada checkout.',
                ]);
            }

            if ((float) $payload['paid_amount'] < $total) {
                throw ValidationException::withMessages([
                    'paid_amount' => 'Jumlah bayar kurang dari total transaksi.',
                ]);
            }

            $transaction->update([
                'total' => $total,
                'change_amount' => round((float) $payload['paid_amount'] - $total, 2),
            ]);

            return [
                'transaction' => $transaction->load('items'),
                'session' => $sessionData,
            ];
        });
    }

    protected function generateCode()
    {
        $today = now()->format('Ymd');
        $count = Transaction::query()
            ->whereDate('created_at', now()->toDateString())
            ->count() + 1;

        return sprintf('TRX-%s-%04d', $today, $count);
    }
}
