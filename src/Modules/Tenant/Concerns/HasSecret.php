<?php

declare(strict_types=1);

namespace Module\Tenant\Concerns;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Module\Tenant\Models\TenantSecret;

trait HasSecret
{
    public function secret(): HasOne
    {
        return $this->hasOne(TenantSecret::class);
    }
}