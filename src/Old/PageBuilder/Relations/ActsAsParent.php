<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Old\PageBuilder\Relations;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as StandardCollection;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;

interface ActsAsParent extends ReferableModel
{
    public function availableModules(): array;

    public function children(): Collection;

    public function adoptChild(ActsAsChild $child, array $attributes = []);

    public function rejectChild(ActsAsChild $child);

    public function presentChildren(): StandardCollection;

    public function relationWithChild(ActsAsChild $child): Relation;

    public function detachAllChildRelations();
}
