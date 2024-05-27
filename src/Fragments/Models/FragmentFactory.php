<?php

namespace Thinktomorrow\Chief\Fragments\Models;

use Illuminate\Database\Eloquent\Relations\Relation;
use Thinktomorrow\Chief\Fragments\Fragment;

class FragmentFactory
{
    public function create(FragmentModel $fragmentModel): Fragment
    {
        return $this->createObject($fragmentModel->key)
            ->setFragmentModel($fragmentModel);
    }

    public function createObject(string $fragmentKey): Fragment
    {
        return app()->make($this->findClassName($fragmentKey));
    }

    private function findClassName(string $fragmentKey): string
    {
        if(! $className = Relation::getMorphedModel($fragmentKey)) {
            throw new \InvalidArgumentException('Fragment by key [' . $fragmentKey . '] cannot be found. Make sure you have registered the fragment via chiefRegister()->fragment().');
        }

        return $className;
    }
}
