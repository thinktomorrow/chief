<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Route;
use Thinktomorrow\Chief\Forms\Fields\Checkbox;
use Thinktomorrow\Chief\Forms\Fields\Date;
use Thinktomorrow\Chief\Forms\Fields\MultiSelect;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Fields\Time;
use Thinktomorrow\Chief\Forms\Layouts\Card;
use Thinktomorrow\Chief\Forms\Layouts\Grid;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values\Day;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values\Slots;

class DateModel extends Model
{
    use UsesContent;

    protected $guarded = [];
    public $table = 'timetable_dates';
    public $timestamps = false;
    public $casts = [
        'slots' => 'array',
        'data' => 'array',
        'date' => 'date',
    ];

    public function getSlots()
    {
        return Slots::make(Day::fromDateTime($this->date), Slots::convertMappedSlots($this->slots));
    }

    public function fields($model): iterable
    {
        yield MultiSelect::make('timetables')
            ->label('Weekschema\'s')
            ->multiple()
            ->description('Op welke weekschema\'s is deze uitzondering van toepassing?')
            ->sync(null, 'id', 'label')
            ->default(! $model->exists ? [Route::getCurrentRoute()->parameter('timetable_id')] : []);

        yield Date::make('date')
            ->required()
            ->label('Datum');

        yield Card::make()->title('Uurschema (voor- en namiddag)')
            ->description('Een leeg veld geeft aan dat je gesloten bent')->items([
                Grid::make()->columns(2)->items([
                    Time::make('slots[0][from]')->columnName('slots.0.from')->tag('not-on-create')->default('08:30'),
                    Time::make('slots[0][until]')->columnName('slots.0.until')->tag('not-on-create')->default('12:00'),
                ]),
                Grid::make()->columns(2)->items([
                    Time::make('slots[1][from]')->columnName('slots.1.from')->tag('not-on-create')->default('13:00'),
                    Time::make('slots[1][until]')->columnName('slots.1.until')->tag('not-on-create')->default('17:00'),
                ]),

                Checkbox::make('closed')
                    ->options([
                        1 => 'Hele dag gesloten',
                    ])
                    ->value(fn () => ($model->exists && empty($model->slots)) ? 1 : null)
                    ->showAsToggle(),
            ]);

        yield Text::make('content')
            ->setLocalizedFormKeyTemplate('content.:locale')
            ->tag('not-on-create')
            ->label('Eigen tekst')
            ->value($model->data['content'] ?? [])
            ->locales()
            ->description('Bijv. "Gesloten op Paasmaandag", "Kantoor dicht want Teambuilding 🥳"');
    }

    public function timetables(): BelongsToMany
    {
        return $this->belongsToMany(app(TimeTableModel::class), 'timetable_date_pivot', 'timetable_date_id', 'timetable_id');
    }
}
