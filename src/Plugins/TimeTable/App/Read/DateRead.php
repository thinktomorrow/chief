<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\App\Read;

use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values\Slot;

interface DateRead
{
    public static function fromMappedData(array $data, array $timeTableIds): static;

    public function getId(): string;

    public function getTimeTableIds(): array;

    public function getDate(): \DateTime;

    /**
     * @return Slot[]
     */
    public function getSlots(): array;

    public function getData(string $key, ?string $index = null, $default = null);
}
