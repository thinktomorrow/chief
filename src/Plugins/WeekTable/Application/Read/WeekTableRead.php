<?php

namespace Thinktomorrow\Chief\Plugins\WeekTable\Application\Read;

interface WeekTableRead
{
    public static function fromMappedData(array $data, array $childEntities): static;

    public function getId(): string;

    public function getLabel(): string;

    /** @return DayRead[] */
    public function getDays(): array;

    public function getData(string $key, string $locale = null, $default = null);
}
