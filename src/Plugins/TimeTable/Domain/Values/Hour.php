<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values;

use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Exceptions\InvalidHourFormat;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Exceptions\InvalidMinutesFormat;

class Hour
{
    private int $hour;
    private int $minutes;

    private function __construct()
    {
    }

    public static function make(int $hour, int $minutes = 0): static
    {
        self::validateConstraints($hour, $minutes);

        $model = new static();
        $model->hour = $hour;
        $model->minutes = $minutes;

        return $model;
    }

    public static function fromFormat(string $hour, string $format = 'H:i'): static
    {
        $dateTime = \DateTime::createFromFormat($format, $hour);

        return static::make((int) $dateTime->format('H'), (int) $dateTime->format('i'));
    }

    public function getHour(): int
    {
        return $this->hour;
    }

    public function getMinutes(): int
    {
        return $this->minutes;
    }

    public function getFormat(string $format = 'g:i'): string
    {
        return \DateTime::createFromFormat('H:i', $this->hour.':'.$this->minutes)->format($format);
    }

    private static function validateConstraints(int $hour, ?int $minutes): void
    {
        if ($hour > 24 || $hour < 0) {
            throw new InvalidHourFormat('Invalid hour given [' . $hour . ']');
        }

        if ($minutes > 60 || $minutes < 0) {
            throw new InvalidMinutesFormat('Invalid minutes given [' . $minutes . ']');
        }
    }
}
