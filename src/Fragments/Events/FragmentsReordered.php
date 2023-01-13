<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Events;

class FragmentsReordered
{
    public int $contextId;

    public function __construct(int $contextId)
    {
        $this->contextId = $contextId;
    }
}
