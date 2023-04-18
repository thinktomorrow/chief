<?php

namespace Thinktomorrow\Chief\Plugins\WeekTable\Domain\Values;

class WeekDay
{
    private string $weekDayId;
    private string $weekDayLabel;

    public static function make(string $weekDayId, string $weekDayLabel): static
    {
        $model = new static();

        $model->weekDayId = $weekDayId;
        $model->weekDayLabel = $weekDayLabel;

        return $model;
    }

    public function getWeekDayId(): string
    {
        return $this->weekDayId;
    }

    public function getWeekDayLabel(): string
    {
        return $this->weekDayLabel;
    }
}
