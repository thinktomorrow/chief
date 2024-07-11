<?php

namespace Thinktomorrow\Chief\TableNew\Table\Concerns;

trait HasTreeReference
{
    private ?string $treeReference = null;

    public function setTreeReference(string $treeReference): static
    {
        $this->treeReference = $treeReference;

        return $this;
    }

    public function getTreeReference(): ?string
    {
        return $this->treeReference;
    }
}
