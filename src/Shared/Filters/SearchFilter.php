<?php

namespace Shared\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class SearchFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): void
    {
        $columns = explode('|', $property);
        $value = mb_strtolower($value, 'UTF-8'); // Convert to lowercase for case-insensitive search

        $query->where(function (Builder $query) use ($columns, $value) {
            foreach ($columns as $column) {
                if (strpos($column, '.')) {
                    $attr = explode('.', $column);
                    if (count($attr) > 2) {
                        $query->orWhereHas($attr[0], fn (Builder $rq) => $rq->whereHas(
                            $attr[1],
                            fn (Builder $rq) => $rq->whereRaw('LOWER(CAST('.$attr[2].' AS CHAR)) LIKE ?', ['%'.$value.'%'])
                        ));
                    } else {
                        $query->orWhereHas(
                            $attr[0],
                            fn (Builder $rq) => $rq->whereRaw('LOWER(CAST('.$attr[1].' AS CHAR)) LIKE ?', ['%'.$value.'%'])
                        );
                    }
                } else {
                    $query->orWhereRaw('LOWER(CAST('.$column.' AS CHAR)) LIKE ?', ['%'.$value.'%']);
                }
            }
        });
    }
}
