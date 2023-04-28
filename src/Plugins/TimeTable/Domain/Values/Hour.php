<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values;

use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Exceptions\InvalidHourFormat;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Exceptions\InvalidMinutesFormat;

class Hour
{
    private string $hour;
    private string $minutes;

    private function __construct()
    {
    }

    public static function make(string $hour, string $minutes = '00'): static
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

        return static::make((string) $dateTime->format('H'), (string) $dateTime->format('i'));
    }

    public function getHour(): string
    {
        return $this->hour;
    }

    public function getMinutes(): string
    {
        return $this->minutes;
    }

    public function getFormat(string $format = 'g:i'): string
    {
        $date = \DateTime::createFromFormat('H:i', $this->hour.':'.$this->minutes);

        return \DateTime::createFromFormat('H:i', $this->hour.':'.$this->minutes)->format($format);
    }

    private static function validateConstraints(string $hour, ?string $minutes): void
    {
        if ((int)$hour > 24 || (int)$hour < 0) {
            throw new InvalidHourFormat('Invalid hour given [' . $hour . ']');
        }

        if (strlen($minutes) < 2 || strlen($minutes) > 2) {
            throw new InvalidMinutesFormat('Invalid minutes given [' . $minutes . ']. Two digits expected.');
        }

        if ((int)$minutes > 60 || (int)$minutes < 0) {
            throw new InvalidMinutesFormat('Invalid minutes given [' . $minutes . ']');
        }
    }
}
