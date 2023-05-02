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
        $until = $from->copy()->addDays((7 * $weeks)-1);

        return $this->forDays($from, $until);
    }

    public function forDays(Carbon $from, Carbon $until): array
    {
        $days = [];

//        $i = 1;
        $period = $from->range($until);

        foreach($period as $day) {
            $days[] = $this->forDate($day);
        }

//        $day = $from->copy();
//        $days[] = $this->forDate($day);
//
//        while($until->gt($day)) {
//            $days[] = $this->forDate($day->copy()->addDays(++$i));
//        }

        return $days;
    }
}
