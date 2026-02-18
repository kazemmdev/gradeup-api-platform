<?php

namespace Shared\Services\Payment;

use Module\Payment\Models\Transaction;

interface PaymentGatewayInterface
{
    public function make(Transaction $transaction, $callback_url): string;

    public function verify(Transaction $transaction): bool|array;
}
