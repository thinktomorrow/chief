<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Events;

class FragmentDuplicated
{
    public function __construct(public readonly string $fragmentId, public readonly string $duplicatedFragmentId, public readonly string $sourceContextId, public readonly string $targetContextId)
    {
    }
}
