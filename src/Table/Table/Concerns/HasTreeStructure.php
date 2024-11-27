<?php

namespace Thinktomorrow\Chief\Table\Table\Concerns;

/**
 * This enables setting the model's title attribute to be used in ancestor breadcrumbs,
 * which appear when displaying a tree structure in the table. The breadcrumbs show
 * the (missing) parent hierarchy for the first row.
 */
trait HasTreeStructure
{
    protected bool $shouldReturnResultsAsTree = false;
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

    public function shouldReturnResultsAsTree(): bool
    {
        return $this->shouldReturnResultsAsTree;
    }

    public function returnResultsAsTree(bool $returnResultsAsTree = true): static
    {
        $this->shouldReturnResultsAsTree = $returnResultsAsTree;

        return $this;
    }
}
