<?php

namespace Thinktomorrow\Chief\Plugins\WeekTable\Infrastructure\Models;

use Illuminate\Support\Carbon;
use Thinktomorrow\Chief\Plugins\WeekTable\Application\Read\DateRead;
use Thinktomorrow\Chief\Plugins\WeekTable\Domain\Model\DateId;
use Thinktomorrow\Chief\Plugins\WeekTable\Domain\Model\WeekTableId;
use Thinktomorrow\Chief\Plugins\WeekTable\Domain\Values\Slots;

class DefaultDateRead implements DateRead
{
    private DateId $dateId;
    private \DateTime $date;
    private Slots $periods;
    private array $data;

    /** @var WeekTableId[] */
    private array $weekTableIds;

    private function __construct()
    {

    }

    public static function fromMappedData(array $data, array $childEntities): static
    {
        $model = new static();

        $model->dateId = DateId::fromString($data['id']);
        $model->date = Carbon::make($data['date']);

        $model->periods = Slots::make(Slots::convertMappedPeriods($data['periods']));
        $model->data = $data['data'] ?? [];

        $model->weekTableIds = array_map(fn($weekTableId) => WeekTableId::fromString($weekTableId), $childEntities[WeekTableModel::class]);

        return $model;
    }

    public function getId(): string
    {
        return $this->dateId->get();
    }

    public function getWeekTableIds(): array
    {
        return array_map(fn(WeekTableId $weekTableId) => $weekTableId->get(), $this->weekTableIds);
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function getSlots(): Slots
    {
        return $this->periods;
    }

    public function getData(string $key, string $index = null, $default = null)
    {
        $key = $index ? $key .'.'.$index : $key;

        return data_get($this->data, $key, $default);
    }
}
