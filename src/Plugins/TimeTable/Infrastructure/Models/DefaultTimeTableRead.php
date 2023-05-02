<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models;

use Thinktomorrow\Chief\Plugins\TimeTable\App\Read\TimeTableRead;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Model\timetableId;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values\SlotsByDay;

class DefaultTimeTableRead implements TimeTableRead
{
    private TimeTableId $timeTableId;

    /** @var SlotsByDay[] */
    private array $days;
    private string $label;
    private array $data;

    private function __construct()
    {

    }

    public static function fromMappedData(array $data, array $days): static
    {
        $model = new static();

        $model->timeTableId = TimeTableId::fromString($data['id']);
        $model->days = array_map(fn ($day) => SlotsByDay::fromMappedData($day['key'], json_decode($day['slots'], true)), $days);
        $model->label = $data['label'];
        $model->data = $data['data'] ?? [];

        return $model;
    }

    public function getId(): string
    {
        return $this->timeTableId->get();
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getDays(): array
    {
        return $this->days;
    }

    public function getData(string $key, string $locale = null, $default = null)
    {
        $key = $locale ? $key .'.'.$locale : $key;

        return data_get($this->data, $key, $default);
    }
}
