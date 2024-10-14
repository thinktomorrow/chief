<?php

namespace Thinktomorrow\Chief\Table\Livewire\Concerns;

use Thinktomorrow\Chief\Shared\Concerns\Nestable\NestableTree;

trait WithReordering {
    public bool $isReordering = true;

    public function startReordering()
    {
        $this->isReordering = true;
    }

    public function stopReordering()
    {
        $this->isReordering = false;
    }

    public function getReorderResults(): NestableTree
    {
        return NestableTree::fromIterable($this->getResults());
    }

    public function reorder()
    {
        dd(func_get_args());
//        $reorderedPreviewFiles = collect($orderedIds)
//            ->map(fn ($previewFileId) => $this->previewFiles[$this->findPreviewFileIndex($previewFileId)])
//            ->all();
//
//        $this->previewFiles = $reorderedPreviewFiles;
    }
}
