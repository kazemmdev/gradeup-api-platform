<?php

declare(strict_types=1);

namespace Module\Tenant\Models;

use Carbon\CarbonInterface;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Module\Tenant\Concerns\HasSecret;
use Module\Tenant\Concerns\HasConfig;

/**
 * Tenant Model:
 *
 * @property int                  $id
 * @property string               $name
 * @property string|null          $title
 * @property string|null          $slug
 * @property string|null          $description
 * @property null|CarbonInterface $created_at
 * @property null|CarbonInterface $updated_at
 */
class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains, HasConfig, HasSecret;

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
        ];
    }

    public function getIncrementing(): bool
    {
        return true;
    }
}
