<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Assistants;

use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

trait StaticFragmentableDefaults
{
    use FragmentableDefaults;

    public function modelReference(): ModelReference
    {
        return ModelReference::fromStatic(static::class);
    }
}
