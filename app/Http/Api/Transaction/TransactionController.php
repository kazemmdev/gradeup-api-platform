<?php

declare(strict_types=1);

namespace App\Http\Api\Transaction;

use App\Http\Controllers\Controller;    
use Module\Payment\Models\Transaction;
use Module\Payment\Actions\VerifyPaymentAction;
use Module\Tenant\Models\Tenant;
use Shared\Services\Payment\PaymentGatewayInterface;


class TransactionController extends Controller
{
    public function store()
    {
        return $this->success([], 201);
    }

    public function update($transaction_id, PaymentGatewayInterface $gateway)
    {
        $tenant = Tenant::query()->where('id', request('client_id'))->firstOrFail();

        $transaction = tenancy()->run($tenant, function () use ($transaction_id) {
            return Transaction::query()->where('id', $transaction_id)->firstOrFail();
        });

        return $this->success(VerifyPaymentAction::execute($transaction, $gateway));
    }
}