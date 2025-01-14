<?php

namespace Thinktomorrow\Chief\Table\Table\Concerns;

use Thinktomorrow\Chief\Table\Columns\Column;
use Thinktomorrow\Chief\Table\Columns\Header;

trait HasColumns
{
    private array $columns = [];

    /** Specific order of columns given by the sequence of the keys. */
    private array $columnKeysInOrder = [];

    private bool $areColumnsOrdered = false;

    public function columns(array $columns): static
    {
        $this->columns = array_merge($this->columns, array_map(fn ($column) => ! $column instanceof Column ? Column::items([$column]) : $column, $columns));

        $this->updateHeaders();

        return $this;
    }

    public function removeColumn(string $key): static
    {
        foreach ($this->columns as $k => $column) {
            foreach ($column->getItems() as $item) {
                if ($item->getKey() === $key) {
                    $column->removeItem($key);

                    if (empty($column->getItems())) {
                        unset($this->columns[$k]);
                    }
                }
            }
        }

        $this->updateHeaders();

        return $this;
    }

    public function orderColumns(array $columnKeysInOrder): static
    {
        $this->columnKeysInOrder = $columnKeysInOrder;

        return $this;
    }

    public function getColumns(): array
    {
        $this->moveColumnsInOrder();

        return $this->columns;
    }

    private function moveColumnsInOrder(): void
    {
        if ($this->areColumnsOrdered || count($this->columnKeysInOrder) === 0) {
            return;
        }

        $columns = [];
        $unOrderedColumns = [];

        foreach ($this->columns as $column) {
            foreach ($column->getItems() as $item) {
                if (in_array($item->getKey(), $this->columnKeysInOrder)) {
                    $columns[(int) array_search($item->getKey(), $this->columnKeysInOrder)] = $column;

                    break;
                } else {
                    $unOrderedColumns[] = $column;

                    break;
                }
            }
        }

        // Sort by non-assoc keys so the desired order is maintained
        ksort($columns);

        $this->columns = array_merge($columns, $unOrderedColumns);

        $this->areColumnsOrdered = true;
        $this->updateHeaders();
    }

    private function updateHeaders(): void
    {
        $this->headers = collect($this->columns)
            ->reject(fn ($column) => empty($column->getItems()))
            ->map(fn ($column) => Header::make($column->getItems()[0]->getLabel()))
            ->all();
    }
}
