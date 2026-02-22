<?php

use Illuminate\Support\Facades\Route;
use App\Http\Api\Transaction\TransactionController;

Route::apiResource('transactions', TransactionController::class)->only('store', 'update');
