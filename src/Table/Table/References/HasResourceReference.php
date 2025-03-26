<?php

namespace Thinktomorrow\Chief\Table\Table\References;

use Thinktomorrow\Chief\Resource\TreeResource;

trait HasResourceReference
{
    private ?ResourceReference $resourceReference = null;

    // Custom tree resource
    private ?TreeResource $treeResource = null;

    public function setResourceReference(ResourceReference $resourceReference): static
    {
        $this->resourceReference = $resourceReference;

        return $this;
    }

    public function getResourceReference(): ?ResourceReference
    {
        return $this->resourceReference;
    }

    public function setTreeResource(TreeResource $treeResource): static
    {
        $this->treeResource = $treeResource;

        return $this;
    }

    public function getTreeResource(): ?TreeResource
    {
        return $this->treeResource;
    }
}
