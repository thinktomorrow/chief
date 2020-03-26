<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\PageBuilder;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Concerns\Viewable\ViewableContract;
use Thinktomorrow\Chief\Modules\Module;
use Thinktomorrow\Chief\Relations\ActsAsChild;
use Thinktomorrow\Chief\Relations\ActsAsParent;
use Thinktomorrow\Chief\Sets\Set;
use Thinktomorrow\Chief\Sets\StoredSetReference;

class PresentSections
{
    /** @var ActsAsParent */
    private $parent;

    /**
     * Original collection of children
     *
     * @var array
     */
    private $children;

    /**
     * Resulting sets
     *
     * @var array
     */
    private $sets;

    /**
     * Keep track of current pageset index key
     *
     * @var string
     */
    private $current_index = null;

    /**
     * Keep track of current pageset type. This is mainly to bundle
     * individual selected pages together.
     *
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
                $this->addSetToCollection($i, $child->toSet($this->parent));
                continue;
            }

            // A module is something we will add as is, without combining them together
            if ($child instanceof Module) {
                $this->sets[$i] = $child;
                $this->current_type = null;
                continue;
            }

            // A model that is not a module will be rendered as a set by default. You can set a 'dontRenderAsSet' property to avoid this behavior
            // and allow for the model to be rendered as a single element.
            if (property_exists($child, 'dontRenderAsSet') && $child->dontRenderAsSet) {
                $this->sets[$i] = $child;
                $this->current_type = null;
                continue;
            }

            $this->addModelToCollection($i, $child);
        }

        return $this->sets->values()->map(function (ViewableContract $child) {
            return ($this->withSnippets && method_exists($child, 'withSnippets'))
                ? $child->withSnippets()->setViewParent($this->parent)->renderView()
                : $child->setViewParent($this->parent)->renderView();
        });
    }

    private function addSetToCollection($index, Set $set)
    {
        $this->sets[$index] = $set;
        $this->current_type = null;
    }

    private function addModelToCollection($index, ActsAsChild $model)
    {
        // Only published pages you fool!
        // TODO: check for assistant instead of method existence
        if (method_exists($model, 'isPublished') && !$model->isPublished()) {
            return;
        }

        $key = $model->viewKey();

        // Set the current collection to the model key identifier: for pages this is the collection key, for
        // other managed models this is the registered key.
        if ($this->current_type == null || $this->current_type != $key) {
            $this->current_type = $key;
            $this->current_index = $index;
        } // If current pageset index is null, let's make sure it is set to the current index
        elseif (is_null($this->current_index)) {
            $this->current_index = $index;
        }

        $this->pushToSet($model, $key);
    }

    private function pushToSet($model, string $setKey)
    {
        if (!isset($this->sets[$this->current_index])) {
            $this->sets[$this->current_index] = new Set([], $setKey);
        }

        $this->sets[$this->current_index]->push($model);
    }
}
