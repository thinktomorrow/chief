<?php

namespace Thinktomorrow\Chief\Common\Relations;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Sets\Set;
use Thinktomorrow\Chief\Sets\StoredSetReference;

class ParseChildrenForPresentation
{
    /** @var ActsAsParent */
    private $parent;

    /**
     * Original collection of children
     * @var array
     */
    private $children;

    /**
     * Resulting collection
     * @var array
     */
    private $collection;

    /**
     * Keep track of current pageset index key
     * @var string
     */
    private $current_pageset_index = null;

    /**
     * Keep track of current pageset type. This is mainly to bundle
     * individual selected pages together.
     * @var string
     */
    private $current_pageset_type = null;

    private $withSnippets = false;

    public function __construct()
    {
        $this->collection = collect([]);
    }

    // pages can be adopted as children individually or as a pageset. They are presented in one module file
    // with the collection of all pages combined. But only if they are sorted right after each other
    public function __invoke(ActsAsParent $parent, Collection $children): Collection
    {
        $this->parent = $parent;
        $this->children = $children;

        $this->withSnippets = $parent->withSnippets;

        return $this->toCollection();
    }

    public function toCollection(): Collection
    {
        foreach ($this->children as $i => $child) {
            if ($child instanceof Page) {
                $this->addPageToCollection($i, $child);
                continue;
            }

            if ($child instanceof StoredSetReference) {
                $this->addSetToCollection($i, $child->toSet());
                continue;
            }

            $this->collection[$i] = $child;
        }

        return $this->collection->map(function (PresentForParent $child) {
            return ($this->withSnippets && method_exists('withSnippets', $child))
                ? $child->withSnippets()->presentForParent($this->parent)
                : $child->presentForParent($this->parent);
        });
    }

    private function addSetToCollection($index, Set $set)
    {
        $this->collection[$index] = $set;
    }

    private function addPageToCollection($index, Page $child)
    {
        // Only published pages you fool!
        if (! $child->isPublished()) {
            return;
        }

        // Set the current pages collection to the current collection type
        if ($this->current_pageset_type == null || $this->current_pageset_type != $child->collectionKey()) {
            $this->current_pageset_type = $child->collectionKey();
            $this->current_pageset_index = $index;
        }
        // If current pageset index is null, let's make sure it is set to the current index
        elseif(is_null($this->current_pageset_index)) {
            $this->current_pageset_index = $index;
        }

        $this->pushPageToCollection($child);
    }

    private function pushPageToCollection(Page $page)
    {
        if (!isset($this->collection[$this->current_pageset_index])) {
            $this->collection[$this->current_pageset_index] = new Set([], $page->collectionKey());
        }

        $this->collection[$this->current_pageset_index]->push($page);
    }
}
