<?php

namespace Shared\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\QueryBuilder\Sorts\Sort;

class RelatedSort implements Sort
{
    public function __invoke(Builder $query, $descending, string $property): void
    {
        $direction = $descending ? 'desc' : 'asc';

        if (strpos($property, '.')) {
            [$relation, $column] = explode('.', $property, 2);

            $model = $query->getModel();
            $relationInstance = $model->{$relation}();

            if ($relationInstance instanceof BelongsTo) {
                // For BelongsTo relationships
                $relatedTable = $relationInstance->getRelated()->getTable();
                $foreignKey = $relationInstance->getForeignKeyName();
                $ownerKey = $relationInstance->getOwnerKeyName();

                $query->select($model->getTable().'.*')
                    ->leftJoin(
                        $relatedTable.' as sort_table',
                        $model->getTable().'.'.$foreignKey,
                        '=',
                        'sort_table.'.$ownerKey
                    )
                    ->orderBy('sort_table.'.$column, $direction);
            } elseif ($relationInstance instanceof HasMany || $relationInstance instanceof HasOne) {
                // For HasMany/HasOne relationships
                $relatedTable = $relationInstance->getRelated()->getTable();
                $foreignKey = $relationInstance->getForeignKeyName();
                $localKey = $relationInstance->getLocalKeyName();

                // Use a subquery to get the related value for sorting
                $subQuery = $relationInstance->getRelated()
                    ->selectRaw("MAX({$column}) as {$column}, {$foreignKey}")
                    ->groupBy($foreignKey);

                $query->leftJoinSub(
                    $subQuery,
                    'sort_table',
                    function ($join) use ($model, $localKey, $foreignKey) {
                        $join->on($model->getTable().'.'.$localKey, '=', 'sort_table.'.$foreignKey);
                    }
                )->orderBy('sort_table.'.$column, $direction);
            }
        }
    }
}
