<?php

namespace Thinktomorrow\Chief\Common\Relations;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\PageSets\PageSet;
use Thinktomorrow\Chief\PageSets\StoredPageSetReference;

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

        return $this->toCollection();
    }

    public function toCollection(): Collection
    {
        foreach ($this->children as $i => $child) {

            if ($child instanceof Page) {
                $this->addPageToCollection($i, $child);
                continue;
            }

            if($child instanceof StoredPageSetReference){
                $this->addPageSetToCollection($i, $child->toPageSet());
                continue;
            }

            $this->collection[$i] = $child;
        }

        return $this->collection->map(function (PresentForParent $child) {
            return $child->presentForParent($this->parent);
        });
    }

    private function addPageSetToCollection($index, PageSet $pageset)
    {
        $this->collection[$index] = $pageset;
    }

    private function addPageToCollection($index, Page $child)
    {
        // Only published pages you fool!
        if (! $child->isPublished()) return;

        // Set the current pages collection to the current collection type
        if ($this->current_pageset_type == null || $this->current_pageset_type != $child->collectionKey()) {
            $this->current_pageset_type = $child->collectionKey();
            $this->current_pageset_index = $index;
        }

        $this->pushPageToCollection($child);

        // Reset the grouped_collection after each loop (keep type so we know when matching pages follow up on each other.
        $this->current_pageset_index = null;
    }

    private function pushPageToCollection(Page $page)
    {
        if (!isset($this->collection[$this->current_pageset_index])) {
            $this->collection[$this->current_pageset_index] = new PageSet();
        }

        $this->collection[$this->current_pageset_index]->push($page);
    }
}