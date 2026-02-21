<?php

declare(strict_types=1);

namespace Shared\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

abstract class TenantAwareJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $tenant_id;

    public function __construct()
    {
        $this->tenant_id = tenant('id');
    }

    public function failed(Throwable $exception): void
    {
        $jobName = static::class;

        Log::error("Job failed permanently: {$jobName}", [
            'tenant_id' => $this->tenant_id,
            'job' => $jobName,
            'exception' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString(),
            'attempts' => $this->attempts(),
            'job_data' => $this->getJobData(),
        ]);

        // Call child class implementation if exists
        $this->handleFailure($exception);
    }

    /**
     * Override this method in child classes to add custom failure handling
     */
    protected function handleFailure(Throwable $exception): void
    {
        // Child classes can override this for custom failure handling
    }

    /**
     * Get relevant job data for logging (override in child classes)
     */
    protected function getJobData(): array
    {
        return [];
    }
}
