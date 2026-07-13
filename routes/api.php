<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\XenditCallbackController; // Pastikan namespace ini ada

// DAFTARKAN DI SINI:
Route::post('/xendit/callback', [XenditCallbackController::class, 'handleCallback']);
