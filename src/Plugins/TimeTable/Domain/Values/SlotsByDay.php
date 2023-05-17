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
        $slots = collect($rawSlots)
            ->reject(fn($rawSlot) => ! isset($rawSlot['from']) && ! isset($rawSlot['until']))
            ->map(fn($rawSlot) => Slot::make(
                isset($rawSlot['from']) ? Hour::fromFormat($rawSlot['from'], 'H:i') : null,
                isset($rawSlot['until']) ? Hour::fromFormat($rawSlot['until'], 'H:i') : null,
            ))->values();

        $slots = $slots
            ->map(function($slot, $i) use($slots){
                if($i === 0 || !$previousUntil = $slots[$i-1]->getUntil()) return $slot;

                return $slot->getFrom() && $slot->getFrom()->beforeOrEqual($previousUntil)
                    ? Slot::make(null, $slot->getUntil())
                    : $slot;
            })
            ->all();

        // Next we will merge slots where there are null values, but only when there are more than one slots to merge
        if(count($slots) < 2) return $slots;

        /** @var Slot $slot */
        foreach($slots as $i => $slot) {
            if($i > 0 && is_null($slot->getFrom())) {
                $slots[$i-1] = Slot::make($slots[$i-1]->getFrom(), $slot->getUntil());
                unset($slots[$i]);
            }

            if($i > 0 && is_null($slots[$i-1]->getUntil())) {
                $slots[$i-1] = Slot::make($slots[$i-1]->getFrom(), $slot->getUntil());
                unset($slots[$i]);
            }

            $slots = array_values($slots);
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
