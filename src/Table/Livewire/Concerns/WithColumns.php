<?php

namespace Thinktomorrow\Chief\Table\Livewire\Concerns;

use Thinktomorrow\Chief\Table\Columns\Column;
use Thinktomorrow\Chief\Table\Columns\ColumnItem;
use Thinktomorrow\Chief\Table\Columns\Header;

trait WithColumns
{
    public function getColumns($model): array
    {
        $columns = $this->getTable()->getColumns();

        // Only show columns that are selected by the admin
        $columns = array_filter($columns, function (Column $column) {
            return $column->contains(fn (ColumnItem $item) => in_array($item->getKey(), $this->getColumnSelection()));
        });

        return array_map(function (Column $column) use ($model) {
            return $column->model($model);
        }, $columns);
    }

    public function getHeaders(): array
    {
        $headers = $this->getTable()->getHeaders();

        // Only show headers that are selected by the admin
        $headers = array_filter($headers, function (Header $column) {
            return in_array($column->getKey(), $this->getColumnSelection());
        });

        return $headers;
    }

    /**
     * The unique key reference to the row. Used to reference
     * each row in the DOM for proper livewire diffing
     */
    public function getRowKey($model): string
    {
        if (is_array($model)) {
            return md5(print_r($model, true));
        }

        return (string) $model->{$this->getModelKeyName()};
    }

    public function getRowView(): string
    {
        return $this->getTable()->getRowView();
    }

    /**
     * Used as label in the ancestor breadcrumb
     */
    public function getAncestorTreeLabel($model): ?ColumnItem
    {
        $columns = $this->getColumns($model);

        foreach ($columns as $column) {
            foreach ($column->getItems() as $columnItem) {
                if ($columnItem->getKey() == $this->getTable()->getTreeLabelColumn()) {
                    return $columnItem;
                }
            }
        }

        return null;
    }
}
