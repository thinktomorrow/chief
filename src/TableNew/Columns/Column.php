<?php

namespace Thinktomorrow\Chief\TableNew\Columns;

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

    //    public function getValues(?string $locale = null): iterable
    //    {
    //        // Retrieve value(s)
    //        $value = $this->getEvaluatedValue($locale);
    //
    //        // Split values
    //        if(! is_iterable($value)) {
    //            $value = [$value];
    //        } elseif($value instanceof Collection) {
    //            $value = $value->all();
    //        }
    //
    //        $values = $this->handleEachValue($value);
    //
    //        return array_map(function ($value) {
    //            return $this->replicate()->value($value);
    //        }, $values);
    //    }

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
