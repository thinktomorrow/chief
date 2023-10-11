<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Resource\Events;

class FragmentsReordered
{
    public function __construct(public readonly string $contextId)
    {
    }
}
