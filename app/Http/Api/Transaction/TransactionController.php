<?php

declare(strict_types=1);

namespace App\Http\Api\Transaction;

use App\Http\Controllers\Controller;
use Module\Payment\Actions\EvaluateTransaction;
use Shared\Services\Payment\ZarinpalPaymentGateway;

class TransactionController extends Controller
{
    public function store(ZarinpalPaymentGateway $gateway)
    {
        return $this->success(EvaluateTransaction::execute($gateway));
    }
}
