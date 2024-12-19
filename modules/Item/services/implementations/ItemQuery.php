<?php

namespace Modules\Item\Services\Implementations;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Item\Model\Item;
use Modules\Item\Services\ItemQueryInterface;

class ItemQuery implements ItemQueryInterface
{
    public function find($id, $relations = [])
    {
        $processedRelations = $this->processRelations($relations);

        return Item::on('mysql:read')
            ->with($processedRelations)
            ->findOrFail($id);
    }

    public function getAllWithOptions(array $options = []): LengthAwarePaginator
    {
        $defaultOptions = [
            'show' => 10,
            'sortBy' => 'id',
            'sortOrder' => 'asc',
            'relations' => []
        ];
        $options = array_merge($defaultOptions, $options);

        $processedRelations = $this->processRelations($options['relations']);

        return Item::on('mysql:read')->orderBy($options['sortBy'], $options['sortOrder'])->with($processedRelations)->paginate($options['show']);
    }

    public function processRelations(array $relations)
    {
        $processedRelations = [];

        foreach ($relations as $relation => $columns) {
            if (is_numeric($relation)) {
                $processedRelations[] = $columns;
                continue;
            }
            if (is_string($columns)) {
                $columns = [$columns];
            }
            if (is_array($columns) && !empty($columns)) {
                if (!in_array('id', $columns)) {
                    $columns[] = 'id';
                }
                $processedRelations[$relation] = function ($query) use ($columns) {
                    $query->select($columns);
                };
            } else {
                $processedRelations[] = $relation;
            }
        }

        return $processedRelations;
    }
}
