<?php

declare(strict_types=1);

namespace Module\Payment\Actions;

use Exception;
use Module\Payment\Enums\PaymentStatus;
use Module\Payment\Models\Transaction;
use Shared\Services\Payment\PaymentGatewayInterface;

class VerifyPaymentAction
{
    /**
     * @throws Exception
     */
    public static function execute(Transaction $transaction, PaymentGatewayInterface $gateway)
    {
        try {
            if ($transaction->status == PaymentStatus::PENDING) {
                $payment = $gateway->verify($transaction);

                if (! ($payment['success'] ?? false)) {
                    throw new \Exception('Payment verification failed.');
                }

                return $payment;
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
