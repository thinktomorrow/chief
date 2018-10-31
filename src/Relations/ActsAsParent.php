<?php

namespace Thinktomorrow\Chief\Relations;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as StandardCollection;

interface ActsAsParent
{
    /**
     * This is the path value for this parent model. This is the string that is
     * used as your view filename. e.g. key 'articles' allows for this model
     * to be represented with 'articles' throughout the view files logic.
     *
     * @return string
     */
    public function viewKey(): string;

    public function children(): Collection;

    public function adoptChild(ActsAsChild $child, array $attributes = []);

    public function rejectChild(ActsAsChild $child);

    public function presentChildren(): StandardCollection;

    public function relationWithChild(ActsAsChild $child): Relation;
}
