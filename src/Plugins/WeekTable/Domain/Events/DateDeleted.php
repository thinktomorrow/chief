<?php

namespace Thinktomorrow\Chief\Plugins\WeekTable\Domain\Events;

use Thinktomorrow\Chief\Plugins\WeekTable\Domain\Model\DateId;

class DateDeleted
{
    public function __construct(public readonly DateId $tagId)
    {

    }
}
