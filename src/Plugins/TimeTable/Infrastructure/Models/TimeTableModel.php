<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Thinktomorrow\Chief\Forms\Fields\Common\FieldPresets;
use Thinktomorrow\Chief\Forms\Fields\MultiSelect;
use Thinktomorrow\Chief\Forms\Fields\Repeat;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Form;
use Thinktomorrow\Chief\Forms\Layouts\Grid;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModelDefault;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;

class TimeTableModel extends Model implements ReferableModel
{
    use ReferableModelDefault;
    use HasDynamicAttributes;

    protected $guarded = [];
    public $table = 'timetables';
    public $timestamps = false;
    public $dynamicKeys = [
        'days',
    ];

    public function getDayForForm($key): array
    {
        dd($this->days);
    }

    public function days(): HasMany
    {
        return $this->hasMany(DayModel::class, 'timetable_id');
    }

    public function fields($model): iterable
    {
        yield FieldPresets::pagetitle(
            Text::make('label')
                ->label('Naam van het schema')
                ->description('Bijv. Openingsuren Gent, Levertijden magazijn, ...')
                ->required()
        );

        yield Form::make('monday_form')->editInSidebar()->items([
            Repeat::make('monday.hours')->tag('not-on-create')->items([
                Grid::make()->columns(2)->items([
                    Text::make('from')->tag('not-on-create'),
                    Text::make('until')->tag('not-on-create'),
                ]),
            ]),

            Text::make('monday.content')
                ->tag('not-on-create')
                ->label('Eigen tekst')
                ->locales()
                ->description('Zet het in korte mensentaal. Bijv. "Gesloten op Paasmaandag", "Kantoor dicht want Teambuilding 🥳"'),
        ]);

        yield Form::make('tuesday_form')->editInSidebar()->items([
            Repeat::make('tuesday.hours')->tag('not-on-create')->items([
                Grid::make()->columns(2)->items([
                    Text::make('from')->tag('not-on-create'),
                    Text::make('until')->tag('not-on-create'),
                ]),
            ]),

            Text::make('tuesday.content')
                ->tag('not-on-create')
                ->label('Eigen tekst')
                ->locales()
                ->description('Zet het in korte mensentaal. Bijv. "Gesloten op Paasmaandag", "Kantoor dicht want Teambuilding 🥳"'),
        ]);

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
