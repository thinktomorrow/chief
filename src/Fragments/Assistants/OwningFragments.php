<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Assistants;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Fragments\Fragment;

/**
 * @deprecated no longer required because ContextOwner is now used instead of FragmentsOwner
 */
trait OwningFragments
{
    public function allowedFragments(): array
    {
        return [];
    }

    public function ownerModel(): Model
    {
        return ($this instanceof Fragment)
            ? $this->fragmentModel()
            : $this;
    }
}
