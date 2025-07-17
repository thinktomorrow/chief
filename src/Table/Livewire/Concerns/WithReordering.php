<?php

namespace Thinktomorrow\Chief\Table\Livewire\Concerns;

use Illuminate\Contracts\Pagination\Paginator as PaginatorContract;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\NestableTree;
use Thinktomorrow\Chief\Shared\Concerns\Sortable\ReorderModels;

trait WithReordering
{
    public bool $isReordering = false;

    public function startReordering()
    {
        $this->verifyReorderingModelClass();

        $this->isReordering = true;
    }

    public function stopReordering()
    {
        $this->isReordering = false;
    }

    public function getReorderResults(): NestableTree|PaginatorContract
    {
        if ($this->isTreeReorderingAllowed()) {
            return NestableTree::fromIterable($this->getResults());
        }

        return $this->getResults();
    }

    public function isTreeReorderingAllowed(): bool
    {
        return $this->shouldReturnResultsAsTree();
    }

    public function reorder(array $orderedIds)
    {
        if (count($orderedIds) < 1) {
            return;
        }

        $this->verifyReorderingModelClass();

        $modelClass = $this->getTable()->getReorderingModelClass();

        app(ReorderModels::class)->handleByModel(new $modelClass, $orderedIds);
    }

    public function moveToParent($itemId, $parentId, array $orderedIds)
    {
        $this->verifyReorderingModelClass();

        $modelClass = $this->getTable()->getReorderingModelClass();

        // Get position of the item in the ordered list
        $itemIndex = array_search($itemId, $orderedIds);

        app(ReorderModels::class)->moveToParent(new $modelClass, $itemId, $parentId, $itemIndex);

        $this->reorder($orderedIds);
    }

    private function verifyReorderingModelClass()
    {
        if (! $this->getTable()->hasValidReorderingModelClass()) {
            throw new \RuntimeException('The table does not have a valid Sortable model class set. Given: ['.$this->getTable()->getReorderingModelClass().']. Please set the modelClass via `Table::setReorderingModelClass`.');
        }
    }
}
