<?php

namespace Thinktomorrow\Chief\Plugins\WeekTable\Infrastructure\Models;

use Thinktomorrow\Chief\Plugins\WeekTable\Domain\Model\WeekTableId;

trait HasWeekTableDefaults
{
    public function getWeekTableId(): ?WeekTableId
    {
        return $this->weektable_id ?: null;
    }
}
