<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Assistants;

trait OwningFragments
{
    public function allowedFragments(): array
    {
        //TODO: rename config to 'fragments' as the default for every model.
        return config('chief.children', []);
    }
}
