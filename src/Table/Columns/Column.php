<?php

namespace Thinktomorrow\Chief\Table\Columns;

use Illuminate\Database\Eloquent\Model;

class Column
{
    /** @var ColumnItem[] */
    private array $items;

    public function __construct(array $items)
    {
        // When string is passed, we transform it to a Text Component.
        $this->items = array_map(fn ($item) => ! $item instanceof ColumnItem ? ColumnText::make($item) : $item, $items);
    }

    public static function items(array $items): static
    {
        return new static($items);
    }

    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * Populate all components with the model instance
     */
    public function model(Model|array $model): static
    {
        foreach ($this->items as $item) {
            $item->model($model);
        }

        return $this;
    }
}
