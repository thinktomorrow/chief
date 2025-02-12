<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Actions\NestableQueries;
use Thinktomorrow\Vine\NodeDefaults;

/**
 * Model logic for handling nesting based on the parent_id construct.
 */
trait NestableDefault
{
    use NodeDefaults;

    public function initializeNestableDefault(): void
    {
        $this->children = new NestableTree;
    }

    public function getId(): string
    {
        return $this->getNodeId();
    }

    public function getNodeLabel(): string
    {
        $resource = app(Registry::class)->findResourceByModel(static::class);

        return $resource->getPageTitleForSelect($this);
    }

    public function getBreadCrumbLabelWithoutRoot(): string
    {
        return $this->getBreadcrumbLabel(true);
    }

    public function getBreadCrumbLabel(bool $withoutRoot = false): string
    {
        $label = $this->getNodeLabel();

        if (! $this->isRootNode()) {
            $label = array_reduce(array_reverse($this->getAncestorNodes()->all()), function ($carry, Nestable $node) use ($withoutRoot) {
                if ($node->isRootNode()) {
                    return $withoutRoot ? $carry : $node->getNodeLabel().': '.$carry;
                }

                return $node->getNodeLabel().' > '.$carry;
            }, $this->getNodeLabel());
        }

        return $label;
    }

    public function getBreadCrumbLabels(): array
    {
        $ancestorLabels = array_reduce($this->getAncestorNodes()->all(), function ($carry, Nestable $node) {
            return array_merge($carry, [$node->getNodeLabel()]);
        }, []);

        return array_merge($ancestorLabels, [$this->getNodeLabel()]);
    }

    public function getParent(): ?Nestable
    {
        if (! $this->parent_id) {
            return null;
        }

        return $this->parentModel;
    }

    public function getAncestors(): array
    {
        if (! $this->parent_id) {
            return [];
        }

        return $this->ancestors()->all();
    }

    private function ancestors(): \Illuminate\Database\Eloquent\Collection
    {
        $ancestorIds = app(NestableQueries::class)->getAncestorIds($this);

        return static::whereIn($this->getKeyName(), $ancestorIds)
            ->get()
            ->sortBy(fn ($model) => array_search($model->id, $ancestorIds))
            ->values();
    }

    /**
     * List of the access path to this nestable model.
     * This array should contain all parents.
     *
     * @return Nestable[]
     *
     * @deprecated use getAncestors instead
     */
    public function getBreadCrumbs(): array
    {
        return $this->getAncestors();
    }

    /**
     * @return Nestable[]
     */
    public function getChildren(): iterable
    {
        return $this->childrenModels;
    }

    /**
     * Nested array of the complete child structure
     * belonging to this parent model.
     */
    public function getDescendants(): NestableTree
    {
        $descendants = static::whereIn(
            $this->getKeyName(),
            $this->getDescendantIds()
        )->get();

        return app(NestableQueries::class)->buildNestedTree($descendants);
    }

    public function getDescendantIds(): array
    {
        return app(NestableQueries::class)->getDescendantIds($this);
    }

    public function getSiblings(): iterable
    {
        if (! $this->exists) {
            return collect();
        }

        // Parent id is either NULL or given id
        return static::where($this->getParentIdName(), $this->{$this->getParentIdName()})
            ->where($this->getKeyName(), '<>', $this->getKey())
            ->get();
    }

    public function parentModel(): HasOne
    {
        return $this->hasOne(static::class, $this->getKeyName(), $this->getParentIdName());
    }

    public function childrenModels(): HasMany
    {
        return $this->hasMany(static::class, $this->getParentIdName(), $this->getKeyName());
    }

    private function getParentIdName(): string
    {
        return 'parent_id';
    }
}
