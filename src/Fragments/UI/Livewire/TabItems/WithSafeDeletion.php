<?php

namespace Thinktomorrow\Chief\Fragments\UI\Livewire\TabItems;

trait WithSafeDeletion
{
    public bool $cannotBeDeleted = false;

    public bool $cannotBeDeletedBecauseOfLastLeft = false;

    public bool $cannotBeDeletedBecauseOfConnectedToSite = false;

    private function setDeletionFlags(): void
    {
        $items = $this->getItemModels();

        if ($items->count() < 2) {
            $this->cannotBeDeletedBecauseOfLastLeft = true;
            $this->cannotBeDeleted = true;
        }

        if (count($this->item->getActiveSites()) > 0) {
            $this->cannotBeDeletedBecauseOfConnectedToSite = true;
            $this->cannotBeDeleted = true;
        }
    }
}
