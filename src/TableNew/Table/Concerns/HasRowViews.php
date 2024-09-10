<?php

namespace Thinktomorrow\Chief\TableNew\Table\Concerns;

use Closure;
use Illuminate\Support\Collection;

trait HasRowViews
{
    protected string $rowView = 'chief-table-new::rows.default';

    public function rowView(string $rowView): static
    {
        $this->rowView = $rowView;

        return $this;
    }

    public function getRowView(): string
    {
        return $this->rowView;
    }
}
