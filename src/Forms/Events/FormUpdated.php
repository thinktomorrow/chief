<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Events;

use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

final class FormUpdated
{
    public function __construct(
        public readonly ModelReference $modelReference,
        public readonly string $formId
    ) {

    }
}
