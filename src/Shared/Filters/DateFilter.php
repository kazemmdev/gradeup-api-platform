<?php

namespace Shared\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class DateFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): void
    {
        $query->whereBetween($property, [$value[0], $value[1]]);
    }
}
