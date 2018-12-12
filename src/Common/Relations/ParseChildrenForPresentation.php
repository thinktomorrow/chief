<?php

namespace Thinktomorrow\Chief\Common\Relations;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Common\Collections\ActsAsCollection;
use Thinktomorrow\Chief\Management\ManagedModel;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\Modules\Module;
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
     * Resulting sets
     * @var array
     */
    private $sets;

    /**
     * Keep track of current pageset index key
     * @var string
     */
    private $current_index = null;

    /**
     * Keep track of current pageset type. This is mainly to bundle
     * individual selected pages together.
     * @var string
     */
    private $current_type = null;

    private $withSnippets = false;

    public function __construct()
    {
        $this->sets = collect([]);
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

            if ($child instanceof StoredSetReference) {
                $this->addSetToCollection($i, $child->toSet());
                continue;
            }

            if ($child instanceof Page) {
                $this->addPageToCollection($i, $child);
                continue;
            }

            if ($child instanceof ManagedModel) {
                $this->addManagedModelToCollection($i, $child);
                continue;
            }

            $this->addToCollection($i, $child);
        }

        return $this->sets->values()->map(function (PresentForParent $child) {
            return ($this->withSnippets && method_exists($child, 'withSnippets'))
                ? $child->withSnippets()->presentForParent($this->parent)
                : $child->presentForParent($this->parent);
        });
    }

    private function addToCollection($index, $child)
    {
        $this->sets[$index] = $child;
        $this->current_type = null;
    }

    private function addSetToCollection($index, Set $set)
    {
        $this->sets[$index] = $set;
        $this->current_type = null;
    }

    private function addPageToCollection($index, $child)
    {
        return $this->addModelToCollection($index, $child, $child->collectionKey());
    }

    private function addManagedModelToCollection($index, $child)
    {
        return $this->addModelToCollection($index, $child, $child->managerKey());
    }

    private function addModelToCollection($index, $child, $key)
    {
        // Only published pages you fool!
        if (method_exists($child, 'isPublished') && ! $child->isPublished()) {
            return;
        }

        // Set the current collection to the model key identifier: for pages this is the collection key, for
        // other managed models this is the registered key.
        if ($this->current_type == null || $this->current_type != $key) {
            $this->current_type = $key;
            $this->current_index = $index;
        }
        // If current pageset index is null, let's make sure it is set to the current index
        elseif (is_null($this->current_index)) {
            $this->current_index = $index;
        }

        $this->pushToSet($child, $key);
    }

    private function pushToSet($model, string $setKey)
    {
        if (!isset($this->sets[$this->current_index])) {
            $this->sets[$this->current_index] = new Set([], $setKey);
        }

        $this->sets[$this->current_index]->push($model);
    }
}
