<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Events;

class FragmentPutOffline
{
    public function __construct(public readonly string $fragmentId)
    {
    }
}
