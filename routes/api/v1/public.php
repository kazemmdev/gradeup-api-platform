<?php

use App\Http\Api\Transaction\TransactionController;
use Illuminate\Support\Facades\Route;

Route::apiResource('transactions', TransactionController::class)->only('store');
