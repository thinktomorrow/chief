<?php

namespace Chief\Common\Relations;

use Illuminate\Database\Eloquent\Collection;

interface ActsAsChild
{
    public function parents(): Collection;

    public function acceptParent(ActsAsParent $parent, array $attributes = []);

    public function rejectParent(ActsAsParent $parent);

    public function presentForParent(ActsAsParent $parent, Relation $relation): string;

    public function relationWithParent(ActsAsParent $parent): Relation;
}