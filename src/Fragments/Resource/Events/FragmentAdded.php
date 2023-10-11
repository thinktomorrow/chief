<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Resource\Events;

class FragmentAdded
{
    public function __construct(public readonly string $fragmentId, public readonly string $contextId)
    {
    }
}
