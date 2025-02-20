<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Thinktomorrow\Chief\Forms\Fields\Checkbox;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Fields\Time;
use Thinktomorrow\Chief\Forms\Layouts\Card;
use Thinktomorrow\Chief\Forms\Layouts\Grid;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values\SlotsByDay;

class DayModel extends Model
{
    use UsesContent;

    protected $guarded = [];

    public $table = 'timetable_days';

    public $timestamps = false;

    public $casts = [
        'slots' => 'array',
        'data' => 'array',
    ];

    public function getSlots()
    {
        return SlotsByDay::fromMappedData($this->weekday, $this->slots);
    }

    public function fields($model): iterable
    {
        yield Card::make()->title('Uurschema (voor- en namiddag)')
            ->description('Een leeg veld geeft aan dat je gesloten bent')->items([
                Grid::make()->columns(2)->items([
                    Time::make('slots[0][from]')->step(1 * 60)->columnName('slots.0.from')->tag('not-on-create')->default('08:30'),
                    Time::make('slots[0][until]')->step(1 * 60)->columnName('slots.0.until')->tag('not-on-create')->default('12:00'),
                    Time::make('slots[1][from]')->step(1 * 60)->columnName('slots.1.from')->tag('not-on-create')->default('13:00'),
                    Time::make('slots[1][until]')->step(1 * 60)->columnName('slots.1.until')->tag('not-on-create')->default('17:00'),
                ]),

                Checkbox::make('closed')
                    ->options([
                        1 => 'Hele dag gesloten',
                    ])
                    ->value(fn () => empty($model->slots) ? 1 : null)
                    ->showAsToggle(),
            ]);

        yield Text::make('content')
            ->setLocalizedFormKeyTemplate('content.:locale')
            ->tag('not-on-create')
            ->label('Eigen tekst')
            ->value($model->data['content'] ?? [])
            ->locales()
            ->description('Bijv. "Woensdagnamiddag gesloten"');
    }

    public function timetable(): BelongsTo
    {
        return $this->belongsTo(app(TimeTableModel::class), 'timetable_id');
    }

    public function getLabel()
    {
        return \Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values\Day::fromIso8601Format($this->weekday)->getLabel();
    }

    public static function createWeekWithDefaults(TimeTableModel $model)
    {
        $existingDays = $model::exists() ? $model::first()->days : collect();

        foreach (range(1, 7) as $weekDay) {

            $day = $existingDays->first(fn ($day) => $day->weekday == $weekDay);
            $slots = $day ? $day->slots : static::defaultSlots($weekDay);

            $model->days()->create([
                'weekday' => $weekDay,
                'slots' => $slots,
            ]);
        }
    }

    private static function defaultSlots($weekDay): array
    {
        return match ((string) $weekDay) {
            '6','7' => [],
            default => [
                ['from' => '08:30', 'until' => '12:00'],
                ['from' => '13:00', 'until' => '17:00'],
            ]
        };
    }

    public function getDayInEnglish(): string
    {
        $map = [
            1 => 'monday',
            2 => 'tuesday',
            3 => 'wednesday',
            4 => 'thursday',
            5 => 'friday',
            6 => 'saturday',
            7 => 'sunday',
        ];

        return $map[$this->weekday];
    }
}
