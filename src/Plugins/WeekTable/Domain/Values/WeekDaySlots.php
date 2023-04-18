<?php

namespace Thinktomorrow\Chief\Plugins\WeekTable\Domain\Values;

use Illuminate\Support\Carbon;

class WeekDaySlots
{
    private WeekDay $weekDay;
    private Slots $slots;

    public static function make(WeekDay $weekDay, Slots $slots): static
    {
        $model = new static();

        $model->weekDay = $weekDay;
        $model->slots = $slots;

        return $model;
    }

    public static function convertMappedWeekDays(string $rawWeekDays): array
    {
        $weekDays = [];

        foreach($rawWeekDays as $rawWeekDay) {
            $weekDays[] = static::make(
                WeekDay::make($rawWeekDay['day'], 'trans...'),
                Slots::make(Slots::convertMappedPeriods($rawWeekDay['slots']))
            );
        }

        return $weekDays;
    }

    public function getWeekDay(): WeekDay
    {
        return $this->weekDay;
    }

    public function getSlots(): Slots
    {
        return $this->slots;
    }

}

