<?php

namespace Thinktomorrow\Chief\Plugins\WeekTable\Application\Read;

use Thinktomorrow\Chief\Plugins\WeekTable\Domain\Values\Day;
use Thinktomorrow\Chief\Plugins\WeekTable\Domain\Values\Slots;

interface DayRead
{
    public static function fromMappedData(array $data): static;

    public function getId(): string;

    public function getWeekTableId(): string;

    public function getDay(): Day;

    public function getSlots(): Slots;

    public function getData(string $key, string $index = null, $default = null);
}
