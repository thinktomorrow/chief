<?php

namespace Thinktomorrow\Chief\Common\Relations;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as StandardCollection;

interface ActsAsParent
{
    public function children(): Collection;

    public function adoptChild(ActsAsChild $child, array $attributes = []);

    public function rejectChild(ActsAsChild $child);

    // TODO: still need to verify is this is actually usable / being used?
    // TODO: Because mostly children will be rendered in a parent and not the other way around...
    public function presentForChild(ActsAsChild $child, Relation $relation): string;

    public function presentChildren(): StandardCollection;

    public function relationWithChild(ActsAsChild $child): Relation;
}
