<?php

namespace Thinktomorrow\Chief\Table\Columns;

class ColumnTag extends ColumnText
{
    protected string $view = 'chief-table::columns.tag';

    private ?string $color = null;

    public function color(string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }
}
