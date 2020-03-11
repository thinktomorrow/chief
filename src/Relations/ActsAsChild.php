<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Relations;

use Illuminate\Database\Eloquent\Collection;
use Thinktomorrow\Chief\FlatReferences\ProvidesFlatReference;

interface ActsAsChild extends ProvidesFlatReference
{
    public function parents(): Collection;

    public function acceptParent(ActsAsParent $parent, array $attributes = []);

    public function rejectParent(ActsAsParent $parent);

    public function relationWithParent(ActsAsParent $parent): Relation;

    public function detachAllParentRelations();
}
