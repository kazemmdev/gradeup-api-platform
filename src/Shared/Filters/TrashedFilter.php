<?php

namespace Shared\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class TrashedFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): void
    {
        if ($value === 'only') {
            $query->onlyTrashed();
        }

        if ($value === 'with') {
            $query->withTrashed();
        }
    }
}
