<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Resource\Events;

class FragmentUpdated
{
    public function __construct(public readonly string $fragmentId)
    {
    }
}
