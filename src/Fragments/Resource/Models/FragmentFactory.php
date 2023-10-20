<?php

namespace Thinktomorrow\Chief\Fragments\Resource\Models;

use Illuminate\Database\Eloquent\Relations\Relation;
use Thinktomorrow\Chief\Fragments\Fragmentable;

class FragmentFactory
{
    public function create(FragmentModel $fragmentModel): Fragmentable
    {
        return app(Relation::getMorphedModel($fragmentModel->key))
            ->setFragmentModel($fragmentModel);
    }
}
