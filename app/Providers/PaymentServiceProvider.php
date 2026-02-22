<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Shared\Services\Payment\PaymentGatewayInterface;
use Shared\Services\Payment\ZarinpalPaymentGateway;

class PaymentServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(PaymentGatewayInterface::class, fn () => new ZarinpalPaymentGateway());
    }
}
