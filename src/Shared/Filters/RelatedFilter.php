<?php

namespace Shared\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\Filters\Filter;

class RelatedFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): void
    {
        $query->whereHas($property, function (Builder $query) use ($value) {
            if (strpos($value, '.')) {
                $attr = explode('.', $value);
                $query->whereIn($attr[0], $attr[1]);
            } else {
                $query->where('id', $value);
            }
        });
    }
}
