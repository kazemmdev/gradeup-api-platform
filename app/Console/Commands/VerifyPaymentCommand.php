<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Module\Payment\Jobs\VerifyPaymentsJob;

class VerifyPaymentCommand extends Command
{
    protected $signature = 'payments:verify';

    protected $description = 'Verify all pending payments';

    public function handle(): void
    {
        VerifyPaymentsJob::dispatch();
    }
}