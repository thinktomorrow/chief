<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Assistants;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Fragments\Fragmentable;

trait OwningFragments
{
    public function allowedFragments(): array
    {
        //TODO: rename config to 'fragments' as the default for every model.
        return config('chief.children', []);
    }

    public function ownerModel(): Model
    {
        return ($this instanceof Fragmentable)
            ? $this->fragmentModel()
            : $this;
    }
}
