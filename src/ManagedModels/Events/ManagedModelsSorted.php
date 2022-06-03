<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Events;

class ManagedModelsSorted
{
    public function __construct(public readonly string $resourceKey, public readonly array $indices)
    {
    }
}
