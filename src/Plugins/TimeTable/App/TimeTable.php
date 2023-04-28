<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\App;

use Spatie\OpeningHours\OpeningHours;

class TimeTable extends OpeningHours
{
    public function forCurrentWeek(): array
    {
        $now = now();
        $monday = $now->copy()->startOfWeek();
        $tuesday = $monday->copy()->addDay();
        $wednesday = $monday->copy()->addDays(2);
        $thursday = $monday->copy()->addDays(3);
        $friday = $monday->copy()->addDays(4);
        $saturday = $monday->copy()->addDays(5);
        $sunday = $monday->copy()->addDays(6);

        return [
            $this->forDate($monday),
            $this->forDate($tuesday),
            $this->forDate($wednesday),
            $this->forDate($thursday),
            $this->forDate($friday),
            $this->forDate($saturday),
            $this->forDate($sunday),
        ];
    }
}
