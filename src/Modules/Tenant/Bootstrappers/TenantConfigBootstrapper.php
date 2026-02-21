<?php

declare(strict_types=1);

namespace Module\Tenant\Bootstrappers;

use Illuminate\Support\Facades\Config;
use Module\Tenant\Models\Tenant;
use Stancl\Tenancy\Contracts\TenancyBootstrapper;
use Stancl\Tenancy\Contracts\Tenant as TenantContract;

class TenantConfigBootstrapper implements TenancyBootstrapper
{
    /**
     * Mapping of tenant config keys to Laravel config keys.
     *
     * Key: Tenant config path (without "config." prefix)
     * Value: Laravel config path
     */
    protected array $configMap = [
        'app.s3.url' => 'app.s3',

        // Storage/Filesystem configs
        'filesystems.disks.s3.bucket' => 'filesystems.disks.s3.bucket',
        'filesystems.disks.s3.region' => 'filesystems.disks.s3.region',
        'filesystems.disks.s3.endpoint' => 'filesystems.disks.s3.endpoint',

        // Payment configs
        'payment.merchant_key' => 'services.payments.zarinpal.merchantID',

        // Google OAuth configs
        'services.google.redirect' => 'services.google.redirect',
    ];

    /**
     * Store original config values so we can restore them.
     */
    protected array $originalConfig = [];

    public function bootstrap(TenantContract $tenant): void
    {
        /** @var Tenant $tenant */
        if (! $tenant->config) {
            return;
        }

        foreach ($this->configMap as $tenantKey => $laravelKey) {
            // Get the value from tenant config
            $value = $tenant->getConfig($tenantKey);

            // If tenant has this config set, apply it to Laravel config
            if ($value !== null) {
                // Store original value for restoration
                $this->originalConfig[$laravelKey] = Config::get($laravelKey);

                // Set the tenant-specific config
                Config::set($laravelKey, $value);
            }
        }

        // Also inject secrets into config if needed
        $this->injectSecrets($tenant);
    }

    public function revert(): void
    {
        // Restore original config values
        foreach ($this->originalConfig as $key => $value) {
            Config::set($key, $value);
        }

        $this->originalConfig = [];
    }

    /**
     * Inject tenant secrets into Laravel config.
     */
    protected function injectSecrets(Tenant $tenant): void
    {
        if (! $tenant->secret) {
            return;
        }

        $secretsMap = [
            'google_client_id' => 'services.google.client_id',
            'google_client_secret' => 'services.google.client_secret',
        ];

        $data = $tenant->secret->data ?? [];

        foreach ($secretsMap as $secretKey => $configKey) {
            if (isset($data[$secretKey]) && $data[$secretKey] !== '') {
                $this->originalConfig[$configKey] = Config::get($configKey);
                Config::set($configKey, $data[$secretKey]);
            }
        }
    }
}
