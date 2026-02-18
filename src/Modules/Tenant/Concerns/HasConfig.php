<?php

declare(strict_types=1);

namespace Module\Tenant\Concerns;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Module\Tenant\Models\TenantConfig;

trait HasConfig
{
    public function config(): HasOne
    {
        return $this->hasOne(TenantConfig::class);
    }

    public function getConfig(string $key, mixed $default = null): mixed
    {
        if (!$this->config) {
            return $default;
        }

        return Arr::get($this->config->data, "config.{$key}", $default);
    }

    public function setConfig(string $key, mixed $value): void
    {
        $data = $this->config->data ?? [];
        Arr::set($data, "config.{$key}", $value);
        $this->config->data = $data;
        $this->config->save();
    }

    public function hasFeature(string $feature): bool
    {
        if (!$this->config) {
            return false;
        }

        return (bool) Arr::get($this->config->data, "features.{$feature}", false);
    }

    public function getLimit(string $limit): int
    {
        if (!$this->config) {
            return 0;
        }

        return (int) Arr::get($this->config->data, "limits.{$limit}", 0);
    }
}   