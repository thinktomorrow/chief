<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values;

class SlotsByDay
{
    private Day $day;

    /** @var Slot[] */
    private array $slots;

    public static function make(Day $day, array $slots): static
    {
        // Type check
        array_map(fn (Slot $slot) => $slot, $slots);

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

    public static function convertMappedSlots(array $rawSlots): array
    {
        $slots = [];

        foreach($rawSlots as $rawSlot) {

            if(! isset($rawSlot['from']) && ! isset($rawSlot['until'])) {
                continue;
            }

            $slots[] = Slot::make(
                isset($rawSlot['from']) ? Hour::fromFormat($rawSlot['from'], 'H:i') : null,
                isset($rawSlot['until']) ? Hour::fromFormat($rawSlot['until'], 'H:i') : null,
            );
        }

        return $slots;
    }

    public function getDay(): Day
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

    public function getSlotsAsString(): array
    {
        return array_map(fn (Slot $slot) => $slot->getAsString(), $this->slots);
    }

}
