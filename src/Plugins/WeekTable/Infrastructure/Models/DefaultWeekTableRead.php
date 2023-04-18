<?php

namespace Thinktomorrow\Chief\Plugins\WeekTable\Infrastructure\Models;

use Thinktomorrow\Chief\Plugins\WeekTable\Application\Read\WeekTableRead;
use Thinktomorrow\Chief\Plugins\WeekTable\Domain\Model\WeekTableId;
use Thinktomorrow\Chief\Plugins\WeekTable\Domain\Values\WeekDaySlots;

class DefaultWeekTableRead implements WeekTableRead
{
    private WeekTableId $weekTableId;

    /** @var WeekDaySlots[] */
    private array $weekDaySlots;

    private string $label;
    private array $data;

    private function __construct()
    {

    }

    public static function fromMappedData(array $data, array $childEntities): static
    {
        $model = new static();

        $model->weekTableId = WeekTableId::fromString($data['id']);
        $model->weekDaySlots = WeekDaySlots::convertMappedWeekDays($childEntities[WeekTableModel::class]);
        $model->label = $data['label'];

        return $model;
    }

    public function getId(): string
    {
        return $this->weekTableId->get();
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function getData(string $key, string $locale = null, $default = null)
    {
        $key = $locale ? $key .'.'.$locale : $key;

        return data_get($this->data, $key, $default);
    }
}
