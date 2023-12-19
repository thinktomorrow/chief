<?php

namespace Thinktomorrow\Chief\Fragments\Resource\Models;

use Illuminate\Database\Eloquent\Relations\Relation;
use Thinktomorrow\Chief\Fragments\Fragmentable;

class FragmentFactory
{
    public function create(FragmentModel $fragmentModel): Fragmentable
    {
        return $this->createObject($fragmentModel->key)
            ->setFragmentModel($fragmentModel);
    }

    public function createObject(string $fragmentKey): Fragmentable
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
