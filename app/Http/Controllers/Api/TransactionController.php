<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Support\ApiResponse;

class TransactionController extends Controller
{
    public function index()
    {
        return ApiResponse::success(
            'Daftar transaksi berhasil diambil.',
            Transaction::query()
                ->with('items')
                ->latest()
                ->limit(50)
                ->get()
        );
    }
}
