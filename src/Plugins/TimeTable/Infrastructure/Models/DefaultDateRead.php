<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models;

use Illuminate\Support\Carbon;
use Thinktomorrow\Chief\Plugins\TimeTable\Application\Read\DateRead;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Model\DateId;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Model\TimeTableId;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values\Day;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values\Slots;

class DefaultDateRead implements DateRead
{
    private DateId $dateId;
    private \DateTime $date;
    private Slots $weekDay;
    private array $data;

    /** @var TimeTableId[] */
    private array $timeTableIds;

    private function __construct()
    {

    }

    public static function fromMappedData(array $data, array $timeTableIds): static
    {
        $model = new static();

        $model->dateId = DateId::fromString($data['id']);
        $model->date = Carbon::make($data['date']);
        $model->weekDay = Slots::make(Day::fromDateTime($model->date), Slots::convertMappedSlots(json_decode($data['slots'], true)));

        $model->data = $data['data'] ?? [];

        $model->timeTableIds = array_map(fn($timeTableId) => TimeTableId::fromString($timeTableId), $timeTableIds);

        return $model;
    }

    public function getId(): string
    {
        return $this->dateId->get();
    }

    public function getTimeTableIds(): array
    {
        return array_map(fn(TimeTableId $timeTableId) => $timeTableId->get(), $this->timeTableIds);
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function getSlots(): array
    {
        return $this->weekDay->getSlots();
    }

    public function getData(string $key, string $index = null, $default = null)
    {
        $key = $index ? $key .'.'.$index : $key;

        return data_get($this->data, $key, $default);
    }
}
