<?php

namespace Thinktomorrow\Chief\Urls\Events;

use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class UrlDeleted
{
    public function __construct(
        public readonly int $id,
        public readonly string $slug,
        public readonly string $locale,
        public readonly ModelReference $modelReference
    ) {}
}
