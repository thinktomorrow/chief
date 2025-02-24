<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Events;

class FragmentsReordered
{
    public function __construct(public readonly string $contextId)
    {
    }
}
