<?php

namespace Thinktomorrow\Chief\Plugins\WeekTable\Application\Read;

use Thinktomorrow\Chief\Plugins\WeekTable\Domain\Values\Slots;

interface DateRead
{
    public static function fromMappedData(array $data, array $childEntities): static;

    public function getId(): string;

    public function getWeekTableIds(): array;

    public function getDate(): \DateTime;

    public function getSlots(): Slots;

    public function getData(string $key, string $index = null, $default = null);
}
