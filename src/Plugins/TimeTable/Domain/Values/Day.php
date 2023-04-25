<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values;

use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Exceptions\InvalidDayFormat;

class Day
{
    /** @var int ISO 8601 numeric representation of the day of the week */
    private int $iso8601WeekDay;
    private string $label;

    public static function make(int $iso8601WeekDay, string $label): static
    {
        self::validateConstraints($iso8601WeekDay);

        $model = new static();

        $model->iso8601WeekDay = $iso8601WeekDay;
        $model->label = $label;

        return $model;
    }

    public static function fromDateTime(\DateTime $dateTime): static
    {
        // ISO 8601 numeric representation of the day of the week
        return static::make(
            $dateTime->format('N'),
            static::mapLabels($dateTime->format('N')),
        );
    }

    public static function fromIso8601Format(int $iso8601Day): static
    {
        return static::make(
            $iso8601Day,
            static::mapLabels($iso8601Day),
        );
    }

    /** @return int ISO 8601 numeric representation of the day of the week */
    public function getIso8601WeekDay(): int
    {
        return $this->iso8601WeekDay;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    private static function mapLabels(int $iso8601WeekDay): string
    {
        self::validateConstraints($iso8601WeekDay);

        return match ($iso8601WeekDay) {
            1 => 'Maandag',
            2 => 'Dinsdag',
            3 => 'Woensdag',
            4 => 'Donderdag',
            5 => 'Vrijdag',
            6 => 'Zaterdag',
            7 => 'Zondag',
        };
    }

    private static function validateConstraints(int $iso8601WeekDay): void
    {
        if ($iso8601WeekDay < 1 || $iso8601WeekDay > 7) {
            throw new InvalidDayFormat($iso8601WeekDay . ' is not a valid ISO 8601 weekday value.');
        }
    }

    public static function fullList(): array
    {
        return array_map(fn($day) => static::fromIso8601Format($day), [1,2,3,4,5,6,7]);
    }
}
