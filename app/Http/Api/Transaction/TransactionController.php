<?php

declare(strict_types=1);

namespace App\Http\Api\Transaction;

use App\Http\Controllers\Controller;    
use Module\Payment\Models\Transaction;
use Module\Payment\Actions\VerifyPaymentAction;
use Module\Tenant\Models\Tenant;
use Shared\Services\Payment\PaymentGatewayInterface;
use Illuminate\Support\Facades\DB;

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

        VerifyPaymentAction::execute($transaction, $gateway);


        $setting = tenancy()->run($tenant, function () {
            return DB::table('settings')->where('name', 'app_url')->firstOrFail();
        });

        return $this->success([
            'callback' => $setting->payload . '/checkout?id='.$transaction->uuid.'&program='.request('program'),
        ]);
    }
}