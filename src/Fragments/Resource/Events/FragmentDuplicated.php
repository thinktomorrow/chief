<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Resource\Events;

class FragmentDuplicated
{
    public function __construct(public readonly string $fragmentId, public readonly string $originContextId, public readonly string $targetContextId)
    {
    }
}
