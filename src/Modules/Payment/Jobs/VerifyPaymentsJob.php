<?php

declare(strict_types=1);

namespace Module\Payment\Jobs;

use Module\Payment\Models\Transaction;
use Module\Payment\Actions\VerifyPaymentAction;
use Module\Payment\Enums\PaymentStatus;
use Illuminate\Support\Facades\Log;
use Shared\Jobs\TenantAwareJob;
use Shared\Services\Payment\ZarinpalPaymentGateway;

class VerifyPaymentsJob extends TenantAwareJob
{
    public function handle(): void
    {
        $payment = new ZarinpalPaymentGateway();

        $transactions = Transaction::query()
            ->where('status', PaymentStatus::PENDING)
            ->where('is_internal', false)
            ->where('created_at', '<', now()->subMinutes(10))
            ->get();

        foreach ($transactions as $transaction) {
            try {
                VerifyPaymentAction::execute($transaction, $payment);
            } catch (\Exception $e) {
                Log::error($e->getMessage(), ['transaction' => $transaction]);
            }
        }
    }
}