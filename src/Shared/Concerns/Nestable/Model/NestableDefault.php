<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Shared\Concerns\Nestable\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Tree\NestedTree;

trait NestableDefault
{
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

        return static::whereIn($this->getKeyName(), $ancestorIds)->get();
    }

    /**
     * List of the access path to this nestable model.
     * This array should contain all parents.
     *
     * @return Nestable[]
     *@deprecated use getAncestors instead
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
    public function getDescendants(): NestedTree
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
