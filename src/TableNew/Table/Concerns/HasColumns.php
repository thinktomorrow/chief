<?php

namespace Thinktomorrow\Chief\TableNew\Table\Concerns;

use Thinktomorrow\Chief\TableNew\Columns\Column;
use Thinktomorrow\Chief\TableNew\Columns\Header;

trait HasColumns
{
    private array $columns = [];

    public function columns(array $columns): static
    {
        $this->columns = array_map(fn ($column) => ! $column instanceof Column ? Column::items([$column]) : $column, $columns);

        // If no headers are explicitly set, we will use the column labels as headers
        if (empty($this->headers)) {
            $this->headers = collect($this->columns)
                ->reject(fn ($column) => empty($column->getItems()))
                ->map(fn ($column) => Header::make($column->getItems()[0]->getLabel()))
                ->all();
        }

        return $this;
    }

    public function getColumns($model): array
    {
        return $this->columns;
    }
}
