<?php

declare(strict_types=1);

namespace Shared\Contract;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

trait HasUuid
{
    public static function bootHasUuid(): void
    {
        static::creating(function (Model $model): void {
            $model->uuid = Uuid::uuid4()->toString();
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}
