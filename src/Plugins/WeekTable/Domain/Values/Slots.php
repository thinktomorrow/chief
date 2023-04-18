<?php

namespace Thinktomorrow\Chief\Plugins\WeekTable\Domain\Values;

use Illuminate\Support\Carbon;

class Slots
{
    /** @var Slot[] */
    private array $slots;

    private function __construct()
    {
    }

    public static function make(array $slots): static
    {
        // Typecheck
        array_map(fn (Slot $slot) => $slot, $slots);

        $model = new static();

        $model->slots = $slots;

        return $model;
    }

    public static function convertMappedPeriods(string $rawPeriods): array
    {
        $slots = [];

        foreach(json_decode($rawPeriods, true) as $rawPeriod) {
            $slots[] = Slot::make(
                Carbon::make($rawPeriod['from']),
                Carbon::make($rawPeriod['until']),
            );
        }

        return $slots;
    }

    public function getSlots(): array
    {
        return $this->slots;
    }
}
