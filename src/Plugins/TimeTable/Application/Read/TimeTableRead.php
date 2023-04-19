<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Application\Read;

use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values\Slots;

interface TimeTableRead
{
    public static function fromMappedData(array $data, array $weekDays): static;

    public function getId(): string;

    public function getLabel(): string;

    /** @return Slots[] */
    public function getDays(): array;

    public function getData(string $key, string $locale = null, $default = null);
}