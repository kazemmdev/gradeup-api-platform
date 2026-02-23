<?php

declare(strict_types=1);

namespace Module\Payment\Jobs;

use Illuminate\Support\Facades\Log;
use Module\Payment\Actions\VerifyPaymentAction;
use Module\Payment\Enums\PaymentStatus;
use Module\Payment\Models\Transaction;
use Shared\Jobs\TenantAwareJob;
use Shared\Services\Payment\ZarinpalPaymentGateway;

class VerifyPaymentsJob extends TenantAwareJob
{
    public function handle(): void
    {
        $gateway = new ZarinpalPaymentGateway();

        $transactions = Transaction::query()
            ->where('status', PaymentStatus::PENDING)
            ->where('is_internal', false)
            ->where('created_at', '<', now()->subMinutes(10))
            ->get();

        foreach ($transactions as $transaction) {
            try {
                $payment = VerifyPaymentAction::execute($transaction, $gateway);
                $transaction->update(['ref_id' => $payment['refId'] ?? null, 'status' => PaymentStatus::VALID]);
            } catch (\Exception $e) {
                $transaction->update(['status' => PaymentStatus::INVALID]);
                Log::error($e->getMessage(), ['transaction' => $transaction]);
            }
        }
    }
}
