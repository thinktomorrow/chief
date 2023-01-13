<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Events;

use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class ManagedModelArchived
{
    public function __construct(public readonly ModelReference $modelReference, public readonly ?ModelReference $redirectReference)
    {
    }
}
