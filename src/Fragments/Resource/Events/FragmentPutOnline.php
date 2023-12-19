<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Resource\Events;

class FragmentPutOnline
{
    public function __construct(public readonly string $fragmentId)
    {
    }
}
