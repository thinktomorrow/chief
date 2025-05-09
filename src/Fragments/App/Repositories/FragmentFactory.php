<?php

namespace Thinktomorrow\Chief\Fragments\App\Repositories;

use Illuminate\Database\Eloquent\Relations\Relation;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Fragments\Models\FragmentModel;

class FragmentFactory
{
    public function create(FragmentModel $fragmentModel): Fragment
    {
        $fragment = $this->createObject($fragmentModel->key);

        $fragment->setFragmentModel($fragmentModel);

        return $fragment;
    }

    public function createObject(string $fragmentKey): Fragment
    {
        return app()->make($this->findClassName($fragmentKey));
    }

    private function findClassName(string $fragmentKey): string
    {
        if (! $className = Relation::getMorphedModel($fragmentKey)) {
            throw new \InvalidArgumentException('Fragment by key ['.$fragmentKey.'] cannot be found. Make sure you have registered the fragment via chiefRegister()->fragment().');
        }

        return $className;
    }
}
