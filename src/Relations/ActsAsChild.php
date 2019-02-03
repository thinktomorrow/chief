<?php

namespace Thinktomorrow\Chief\Relations;

use Illuminate\Database\Eloquent\Collection;
use Thinktomorrow\Chief\FlatReferences\ProvidesFlatReference;

interface ActsAsChild extends ProvidesFlatReference
{
    /**
     * This is the path value for this child model. This is the string that is
     * used as your view filename. e.g. key 'articles' allows for this model
     * to be represented with 'articles' throughout the view files logic.
     *
     * @return string
     */
    public function viewKey(): string;

    public function parents(): Collection;

    public function acceptParent(ActsAsParent $parent, array $attributes = []);

    public function rejectParent(ActsAsParent $parent);

    public function relationWithParent(ActsAsParent $parent): Relation;
}
