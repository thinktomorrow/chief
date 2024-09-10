<?php

namespace Thinktomorrow\Chief\TableNew\Table\Concerns;

use Closure;
use Illuminate\Support\Collection;

/**
 * This enables setting the model's title attribute to be used in ancestor breadcrumbs,
 * which appear when displaying a tree structure in the table. The breadcrumbs show
 * the (missing) parent hierarchy for the first row.
 */
trait HasTreeLabelColumn
{
    protected string $treeLabelColumn = 'title';

    public function treeLabelColumn(string $treeLabelColumn): static
    {
        $this->treeLabelColumn = $treeLabelColumn;

        return $this;
    }

    public function getTreeLabelColumn(): string
    {
        return $this->treeLabelColumn;
    }
}
