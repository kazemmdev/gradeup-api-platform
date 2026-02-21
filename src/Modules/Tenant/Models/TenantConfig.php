<?php

declare(strict_types=1);

namespace Module\Tenant\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property null|CarbonInterface $created_at
 * @property null|CarbonInterface $updated_at
 */
class TenantConfig extends Model
{
    protected $table = 'tenant_configs';

    protected $guarded = [];

    protected $casts = [
        'data' => 'array',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
