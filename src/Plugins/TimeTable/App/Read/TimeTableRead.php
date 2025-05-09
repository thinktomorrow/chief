<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\App\Read;

use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values\SlotsByDay;

interface TimeTableRead
{
    public static function fromMappedData(array $data, array $weekDays): static;

    public function getId(): string;

    public function getLabel(): string;

    /** @return SlotsByDay[] */
    public function getDays(): array;

    public function getData(string $key, ?string $locale = null, $default = null);
}
