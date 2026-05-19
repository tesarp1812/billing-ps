<?php

use App\Support\ApiResponse;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return ApiResponse::success('PS Backend API is running.', [
        'docs' => 'Import ps-backend.postman_collection.json',
        'health' => url('/api/health'),
    ]);
});
