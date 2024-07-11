<?php

namespace Thinktomorrow\Chief\TableNew\Columns;

use Carbon\Carbon;
use Thinktomorrow\Chief\Forms\Fields\Concerns\HasValue;

class ColumnDate extends ColumnText
{
    use HasValue{
        getValue as getDefaultValue;
    }

    protected string $view = 'chief-table-new::columns.text';
    private string $format = 'Y-m-d H:i';

    public function getValue(?string $locale = null): mixed
    {
        $value = $this->getDefaultValue($locale);

        return ($value && $value instanceof Carbon) ? $value->format($this->format) : null;
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
}
