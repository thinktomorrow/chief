<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Events;

final class FragmentMoved
{
    public function __construct(public readonly string $contextId, public readonly string $fragmentId) {}
}
