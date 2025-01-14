<?php

namespace Thinktomorrow\Chief\Table\Table\Concerns;

trait HasRowViews
{
    protected string $rowView = 'chief-table::rows.default';

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
