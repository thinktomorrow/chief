<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Domain\Events;

use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Model\DateId;

class DateUpdated
{
    public function __construct(public readonly DateId $tagId)
    {

    }
}
