<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Events;

use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class ManagedModelCreated
{
    public ModelReference $modelReference;

    public function __construct(ModelReference $modelReference)
    {
        $this->modelReference = $modelReference;
    }
}
