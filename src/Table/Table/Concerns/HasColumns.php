<?php

namespace Thinktomorrow\Chief\Table\Table\Concerns;

use Thinktomorrow\Chief\Table\Columns\Column;
use Thinktomorrow\Chief\Table\Columns\Header;

trait HasColumns
{
    private array $columns = [];

    public function columns(array $columns): static
    {
        $this->columns = array_merge($this->columns, array_map(fn ($column) => ! $column instanceof Column ? Column::items([$column]) : $column, $columns));

        $this->rebaseHeaders();

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

        $this->rebaseHeaders();

        return $this;
    }

    public function orderColumns(array $keysInOrder): static
    {
        $ordered = [];
        $unOrdered = [];

        // Sanitize keys to match the column key formatting
        $keysInOrder = array_map(fn ($key) => strtolower($key), $keysInOrder);

        foreach ($this->columns as $column) {
            foreach ($column->getItems() as $item) {
                if (in_array($item->getKey(), $keysInOrder)) {
                    $ordered[(int) array_search($item->getKey(), $keysInOrder)] = $column;

                    break;
                } else {
                    $unOrdered[] = $column;

                    break;
                }
            }
        }

        // Sort by non-assoc keys so the desired order is maintained
        ksort($ordered);

        $this->columns = array_merge($ordered, $unOrdered);

        $this->rebaseHeaders();

        return $this;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    private function rebaseHeaders(): void
    {
        $this->headers = collect($this->columns)
            ->reject(fn ($column) => empty($column->getItems()))
            ->map(fn ($column) => Header::makeHeader($column->getItems()[0]->getKey(), $column->getItems()[0]->getLabel()))
            ->all();
    }
}
