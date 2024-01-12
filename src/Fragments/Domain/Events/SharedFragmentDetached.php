<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Domain\Events;

class SharedFragmentDetached
{
    public function __construct(public readonly string $fragmentId)
    {
    }
}
