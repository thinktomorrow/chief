<?php

namespace Thinktomorrow\Chief\Table\Columns;

use Carbon\Carbon;

class ColumnDate extends ColumnItem
{
    protected string $view = 'chief-table::columns.date';

    private string $format = 'Y-m-d H:i';

    public function getValue(?string $locale = null): string|int|null|float|\Stringable
    {
        $value = parent::getValue($locale);

        if (! $value) {
            return null;
        }

        return ($value instanceof Carbon) ? $value->format($this->format) : $value;
    }

    public function format(string $format): static
    {
        $this->format = $format;

        return $this;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    protected function replicateToItem($value): static
    {
        $item = parent::replicateToItem($value);

        if ($this->format) {
            $item->format($this->format);
        }

        return $item;
    }
}
