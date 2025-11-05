<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Tags;

use Thinktomorrow\Chief\Forms\Concerns\WithComponents;

trait WithTaggedComponents
{
    use WithComponents;

    public function filterByTagged($tag): static
    {
        if (is_string($tag) && $tag === 'untagged') {
            return $this->filterByUntagged();
        }

        return $this->filterTagsRecursive($this, function (HasTags $component) use ($tag) {
            return $component->isTagged($tag);
        });
    }

    public function filterByNotTagged($tag): static
    {
        return $this->filterTagsRecursive($this, function (HasTags $component) use ($tag) {
            return ! $component->isTagged($tag);
        });
    }

    public function filterByUntagged(): static
    {
        return $this->filterTagsRecursive($this, function (HasTags $component) {
            return $component->isUntagged();
        });
    }

    /**
     * The stopRecursiveCallback callable is set for when to stop the recursive when this function returns false.
     * This is used internally for explicitly stop nested fields detection such as a nested repeat field.
     *
     * @param  array  $components
     */
    private function filterTagsRecursive(HasTaggedComponents $parentComponent, callable $filter): static
    {
        $components = $parentComponent->getComponents();

        foreach ($components as $i => $component) {
            if ($component instanceof HasTags && call_user_func($filter, $component) === false) {
                unset($components[$i]);

                continue;
            }

            if ($component instanceof HasTaggedComponents) {
                $this->filterTagsRecursive($component, $filter);
            }
        }

        $parentComponent->components(array_values($components));

        return $this;
    }
}
