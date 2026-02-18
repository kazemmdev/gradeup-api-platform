<?php

namespace Shared\Filters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class DateExpiredFilter implements Filter
{
    /**
     * Filter records where the specified datetime column is less than current time
     *
     * @param  mixed  $value  Boolean to determine if we want expired (true) or not expired (false) records
     * @param  string  $property  The datetime column to check
     */
    public function __invoke(Builder $query, $value, string $property): void
    {
        $now = Carbon::now();

        if ($value === true) {
            // Get expired records (where date is less than now)
            $query->where($property, '<', $now);
        } elseif ($value === false) {
            // Get non-expired records (where date is greater than or equal to now)
            $query->where($property, '>=', $now);
        }
    }
}
