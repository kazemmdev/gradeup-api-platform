<?php

namespace Shared\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class CouponStatusFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): void
    {
        if ($value == 'expired') {
            $query->where('expired_at', '<', now());
        }

        if ($value == 'inactive') {
            $query->where('active', '=', 0)->where('expired_at', '>', now());
        }

        if ($value == 'active') {
            $query->where('active', '=', 1)->where('expired_at', '>', now());
        }
    }
}
