<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\App;

use Carbon\Carbon;
use Spatie\OpeningHours\OpeningHours;

class TimeTable extends OpeningHours
{
    public function forCurrentWeek(): array
    {
        return $this->forWeeks(1);
    }

    public function forWeeks(int $weeks = 2, int $weekStartsAt = 1): array
    {
        $from = now()->startOfWeek($weekStartsAt);
        $until = $from->copy()->addDays((7 * $weeks) - 1);

        return $this->forDays($from, $until);
    }

    public function forDays(Carbon $from, Carbon $until): array
    {
        $result = [];

        foreach ($from->range($until) as $date) {
            $result[$date->format('Y-m-d H:i:s')] = $this->forDate($date);
        }

        return $result;
    }

    public function isException(Carbon $date): bool
    {
        $dateFormatted = $date->format('Y-m-d');
        $exceptions = array_keys($this->exceptions);

        return in_array($dateFormatted, $exceptions);
    }
}
