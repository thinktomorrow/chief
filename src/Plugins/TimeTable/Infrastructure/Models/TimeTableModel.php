<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Thinktomorrow\Chief\Forms\Fields\MultiSelect;
use Thinktomorrow\Chief\Forms\Fields\Repeat;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Form;
use Thinktomorrow\Chief\Forms\Layouts\Card;
use Thinktomorrow\Chief\Forms\Layouts\Grid;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values\Day;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModelDefault;

class TimeTableModel extends Model implements ReferableModel
{
    use ReferableModelDefault;

    protected $guarded = [];
    public $table = 'timetables';
    public $timestamps = false;

    public function getDayContentForForm($weekday, ?string $locale = null): ?string
    {
        if(! $model = $this->findDay($weekday)) {
            return null;
        }

        return $model->getContent($locale);
    }

    public function getSlotForForm($weekday, $index, string $key): ?string
    {
        if(! $model = $this->findDay($weekday)) {
            return null;
        }

        $slot = $model->slots[$index] ?? ['from' => '', 'until' => ''];

        return $slot[$key];
    }

    private function findDay($weekday): ?DayModel
    {
        return $this->days->first(fn ($day) => $day->weekday == $weekday);
    }

    public function days(): HasMany
    {
        return $this->hasMany(DayModel::class, 'timetable_id');
    }

    public function fields($model): iterable
    {
        yield Text::make('label')
                ->label('Naam van het schema')
                ->description('Bijv. Openingsuren Gent, Levertijden magazijn, ...')
                ->required();

        /** @var Day $day */
        foreach(Day::fullList() as $day) {
            yield Card::make()->title($day->getLabel())->items([
                Grid::make()->columns(2)->items([
                    Text::make('days.'.$day->getIso8601WeekDay().'.slots.0.from')
                        ->name('days['.$day->getIso8601WeekDay().'][slots][0][from]')
                        ->tag('not-on-create'),
                    Text::make('days.'.$day->getIso8601WeekDay().'.slots.0.until')
                        ->name('days['.$day->getIso8601WeekDay().'][slots][0][until]')
                        ->tag('not-on-create'),
                ]),
                Grid::make()->columns(2)->items([
                    Text::make('days.'.$day->getIso8601WeekDay().'.slots.1.from')
                        ->name('days['.$day->getIso8601WeekDay().'][slots][1][from]')
                        ->tag('not-on-create'),
                    Text::make('days.'.$day->getIso8601WeekDay().'.slots.1.until')
                        ->name('days['.$day->getIso8601WeekDay().'][slots][1][until]')
                        ->tag('not-on-create'),
                ]),
                Text::make('days.'.$day->getIso8601WeekDay().'.content')
                    ->name('days['.$day->getIso8601WeekDay().'][content][:locale]')
                    ->tag('not-on-create')
                    ->label('Eigen tekst')
                    ->locales()
                    ->description('Zet het in korte mensentaal. Bijv. "Gesloten op Paasmaandag", "Kantoor dicht want Teambuilding 🥳"'),
            ]);
        }

        //        yield Form::make('monday_form')->editInSidebar()->items([
        //            Repeat::make('monday.hours')->tag('not-on-create')->items([
        //                Grid::make()->columns(2)->items([
        //                    Text::make('from')->tag('not-on-create'),
        //                    Text::make('until')->tag('not-on-create'),
        //                ]),
        //            ]),
        //
        //            Text::make('monday.content')
        //                ->tag('not-on-create')
        //                ->label('Eigen tekst')
        //                ->locales()
        //                ->description('Zet het in korte mensentaal. Bijv. "Gesloten op Paasmaandag", "Kantoor dicht want Teambuilding 🥳"'),
        //        ]);
        //
        //        yield Form::make('tuesday_form')->editInSidebar()->items([
        //            Repeat::make('tuesday.hours')->tag('not-on-create')->items([
        //                Grid::make()->columns(2)->items([
        //                    Text::make('from')->tag('not-on-create'),
        //                    Text::make('until')->tag('not-on-create'),
        //                ]),
        //            ]),
        //
        //            Text::make('tuesday.content')
        //                ->tag('not-on-create')
        //                ->label('Eigen tekst')
        //                ->locales()
        //                ->description('Zet het in korte mensentaal. Bijv. "Gesloten op Paasmaandag", "Kantoor dicht want Teambuilding 🥳"'),
        //        ]);

        //        yield Repeat::make('days')->tag('not-on-create')->items([
        //           MultiSelect::make('day')
        //               ->tag('not-on-create')
        //                ->label('Dag van de week')
        //                ->options([
        //                    'maandag',
        //                    'dindag',
        //                    'woensdag',
        //                    'donderdag',
        //                    'vrijdag',
        //                    'zaterdag',
        //                    'zondag',
        //                ]),
        //
        //           Repeat::make('hours')->tag('not-on-create')->items([
        //                Grid::make()->columns(2)->items([
        //                    Text::make('from')->tag('not-on-create'),
        //                    Text::make('until')->tag('not-on-create'),
        //                ]),
        //            ]),
        //
        //           Text::make('content')
        //               ->tag('not-on-create')
        //                ->label('Eigen tekst')
        //                ->locales()
        //                ->description('Zet het in korte mensentaal. Bijv. "Gesloten op Paasmaandag", "Kantoor dicht want Teambuilding 🥳"'),
        //        ]);
    }
}
