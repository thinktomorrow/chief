<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values;

class Slots
{
    private Day $day;

    /** @var Slot[] */
    private array $slots;

    public static function make(Day $day, array $slots): static
    {
        // Type check
        array_map(fn(Slot $slot) => $slot, $slots);

        $model = new static();

        $model->day = $day;
        $model->slots = $slots;

        return $model;
    }

    public static function fromMappedData(string $iso8601Day, array $slots): static
    {
        return static::make(
            Day::fromIso8601Format($iso8601Day),
            static::convertMappedSlots($slots)
        );
    }

    public static function convertMappedWeekDays(array $rawWeekDays): array
    {
        $days = [];

        foreach($rawWeekDays as $rawWeekDay) {
            $days[] = static::make(
                Day::make($rawWeekDay['day'], 'trans...'),
                static::convertMappedSlots($rawWeekDay['slots'])
            );
        }

        return $days;
    }

    public static function convertMappedSlots(array $rawSlots): array
    {
        $slots = [];

        foreach($rawSlots as $rawSlot) {
            $slots[] = Slot::make(
                Hour::fromFormat($rawSlot['from'], 'H:i'),
                Hour::fromFormat($rawSlot['until'], 'H:i'),
            );
        }

        return $slots;
    }

    public function getWeekDay(): Day
    {
        return $this->day;
    }

    /**
     * @return Slot[]
     */
    public function getSlots(): array
    {
        return $this->slots;
    }

}

