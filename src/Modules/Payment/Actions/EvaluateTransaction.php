<?php

declare(strict_types=1);

namespace Module\Payment\Actions;

use Module\Payment\Enums\PaymentStatus;
use Module\Payment\Models\Transaction;
use Module\Tenant\Models\Tenant;
use Shared\Services\Payment\PaymentGatewayInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EvaluateTransaction
{
    public static function execute(PaymentGatewayInterface $gateway)
    {
        // find tentant
        $tenant = Tenant::query()->where('id', request('tenant'))->first();

        if (! $tenant) {
            return [
                'success' => false,
                'message' => 'Tenant not found.',
            ];
        }

        // find app url
        $app_setting = $tenant->run(fn () => DB::table('settings')->where('name', 'app_url')->first());
        $app_url = trim($app_setting?->payload ?? '', '"');

        // find transaction
        $transaction = $tenant->run(fn () => Transaction::query()->where('uuid', request('transaction'))->first());

        if (! $transaction) {
            return [
                'success' => false,
                'message' => 'Transaction not found.',
            ];
        }

        // verify transaction
        try {
            $payment = VerifyPaymentAction::execute($transaction, $gateway);
            $tenant->run(function () use ($payment) {
                Transaction::query()->where('uuid', request('transaction'))->first()
                    ->update(['ref_id' => $payment['refId'] ?? null, 'status' => PaymentStatus::VALID]);
            });

            return [
                'success' => true,
                'callback' => $app_url.'/checkout?id='.$transaction->uuid.'&program='.request('program'),
            ];
        } catch (\Exception $e) {
            $tenant->run(function () {
                Transaction::query()->where('uuid', request('transaction'))->first()
                    ->update(['status' => PaymentStatus::INVALID]);
            });
            
             Log::error($e->getMessage());
        }


        // return callback url
        return [
            'success' => false,
            'callback' => $app_url ?? "https://gradeup.app",
        ];
    }
}
