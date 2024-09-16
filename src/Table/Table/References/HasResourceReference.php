<?php

namespace Thinktomorrow\Chief\Table\Table\References;

trait HasResourceReference
{
    private ?ResourceReference $resourceReference = null;

    public function setResourceReference(ResourceReference $resourceReference): static
    {
        $this->resourceReference = $resourceReference;

        return $this;
    }

    public function getResourceReference(): ?ResourceReference
    {
        return $this->resourceReference;
    }
}
