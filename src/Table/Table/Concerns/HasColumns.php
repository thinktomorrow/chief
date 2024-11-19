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

        $this->updateHeaders();

        return $this;
    }

    public function removeColumn(string $key): static
    {
        foreach($this->columns as $k => $column) {
            foreach($column->getItems() as $item) {
                if ($item->getKey() === $key) {
                    $column->removeItem($key);

                    if(empty($column->getItems())) {
                        unset($this->columns[$k]);
                    }
                }
            }
        }

        $this->updateHeaders();

        return $this;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    private function updateHeaders(): void
    {
        $this->headers = collect($this->columns)
            ->reject(fn ($column) => empty($column->getItems()))
            ->map(fn ($column) => Header::make($column->getItems()[0]->getLabel()))
            ->all();
    }
}
