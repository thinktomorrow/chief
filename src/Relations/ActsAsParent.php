<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Relations;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as StandardCollection;

interface ActsAsParent
{
    public function children(): Collection;

    public function adoptChild(ActsAsChild $child, array $attributes = []);

    public function rejectChild(ActsAsChild $child);

    public function presentChildren(): StandardCollection;

    public function relationWithChild(ActsAsChild $child): Relation;

    public function detachAllChildRelations();
}
