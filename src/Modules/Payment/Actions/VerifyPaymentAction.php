<?php

declare(strict_types=1);

namespace Module\Payment\Actions;

use Module\Payment\Enums\PaymentStatus;
use Module\Payment\Models\Transaction;
use Shared\Services\Payment\PaymentGatewayInterface;
use Exception;

class VerifyPaymentAction
{
    /**
     * @param Transaction $transaction
     * @param PaymentGatewayInterface $gateway
     * 
     * @throws Exception
     */
    public static function execute(Transaction $transaction, PaymentGatewayInterface $gateway): void
    {
        try {
            if ($transaction->status == PaymentStatus::PENDING) {
                $payment = $gateway->verify($transaction);

                if (! ($payment['success'] ?? false)) {
                    $transaction->update([ 'status' => PaymentStatus::INVALID ]);
                    throw new \Exception('Payment verification failed.');
                }

                $transaction->update([
                    'ref_id' => $payment['refId'] ?? null,
                    'status' => PaymentStatus::VALID,
                ]);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
