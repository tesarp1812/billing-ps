<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Checkout\CheckoutRequest;
use App\Services\Billing\CheckoutService;
use App\Support\ApiResponse;

class CheckoutController extends Controller
{
    public function __invoke(CheckoutRequest $request, CheckoutService $checkoutService)
    {
        return ApiResponse::success(
            'Checkout berhasil diproses.',
            $checkoutService->handle($request->validated(), $request->user()->id),
            201
        );
    }
}
