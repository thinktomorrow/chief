<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Events;

class FragmentPutOffline
{
    public int $fragmentModelId;

    public function __construct(int $fragmentModelId)
    {
        $this->fragmentModelId = $fragmentModelId;
    }
}
