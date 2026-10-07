<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Export\Events;

final readonly class ChiefExportCompleted
{
    public function __construct(public string $kind, public ?string $resource, public string $occurredAt) {}
}
