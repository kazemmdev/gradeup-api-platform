<?php

namespace Shared\Services\Payment;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Module\Payment\Models\Transaction;

class ZarinpalPaymentGateway implements PaymentGatewayInterface
{
    /**
     * @throws Exception
     */
    public function make(Transaction $transaction, $callback_url): string
    {
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'ZarinPal Rest Api v1',
                'Content-Type' => 'application/json',
            ])->post(config('services.payments.zarinpal.requestGate'), [
                'amount' => $transaction->amount * 10,
                'callback_url' => $callback_url,
                'merchant_id' => config('services.payments.zarinpal.merchantID'),
                'description' => 'Application Payment Redirect',
            ]);

            $result = $response->json();

            if (array_key_exists('code', $result['data']) && $result['data']['code'] != 100) {
                throw new Exception('امکان اتصال به درگاه پرداخت وجود ندارد.');
            }

            $confirm_id = Arr::get($result, 'data.authority');

            $transaction->update(['confirm_id' => $confirm_id]);

            return config('services.payments.zarinpal.payUrl').$confirm_id;
        } catch (Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @throws Exception
     */
    public function verify(Transaction $transaction): bool|array
    {
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'ZarinPal Rest Api v1',
                'Content-Type' => 'application/json',
            ])->post(config('services.payments.zarinpal.verificationGate'), [
                'amount' => $transaction->amount * 10,
                'authority' => $transaction->confirm_id,
                'merchant_id' => config('services.payments.zarinpal.merchantID'),
            ]);
            $result = $response->json();

            $payment = Arr::get($result, 'data');
            $payment_status = Arr::get($payment, 'code');
            $payment_success = $payment_status == 100 || $payment_status == 101;
            $payment_ref = Arr::get($payment, 'ref_id');

            return [
                'code' => $payment_status,
                'refId' => $payment_ref,
                'success' => $payment_success,
            ];
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
        }
    }
}
