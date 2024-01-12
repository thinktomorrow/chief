<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Domain\Events;

class FragmentDetached
{
    public function __construct(public readonly string $fragmentId, public readonly string $contextId)
    {
    }
}
