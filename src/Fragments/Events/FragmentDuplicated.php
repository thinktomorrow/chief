<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Events;

class FragmentDuplicated
{
    public int $fragmentModelId;
    public int $contextId;

    public function __construct(int $fragmentModelId, int $contextId)
    {
        $this->fragmentModelId = $fragmentModelId;
        $this->contextId = $contextId;
    }
}
