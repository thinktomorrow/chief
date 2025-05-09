<?php

namespace Thinktomorrow\Chief\Sites\Events;

use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class ModelSitesUpdated
{
    public function __construct(
        public readonly ModelReference $modelReference,
        public readonly array $newState,
        public readonly array $previousState
    ) {}
}
