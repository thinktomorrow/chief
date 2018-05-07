<?php

namespace Chief\Common\Relations;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as StandardCollection;

interface ActsAsParent
{
    public function children(): Collection;

    public function adoptChild(ActsAsChild $child, array $attributes = []);

    public function rejectChild(ActsAsChild $child);

    public function presentForChild(ActsAsChild $child, Relation $relation): string;

    public function presentChildren(): StandardCollection;

    public function getCompositeKey(): string;

    public function relationWithChild(ActsAsChild $child): Relation;
}