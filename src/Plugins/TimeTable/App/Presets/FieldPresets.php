<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\TimeTable\App\Presets;

use Thinktomorrow\Chief\Forms\Fields\MultiSelect;
use Thinktomorrow\Chief\Forms\Form;
use Thinktomorrow\Chief\Plugins\TimeTable\App\HasTimeTable;
use Thinktomorrow\Chief\Plugins\TimeTable\App\Read\TimeTableReadRepository;

class FieldPresets
{
    public static function timetable(HasTimeTable $model): iterable
    {
        yield Form::make('timetable')
            ->title('Openingsuren')
            ->editInSidebar()
            ->items([
                MultiSelect::make('timetable_id')
                    ->options(fn () => app(TimeTableReadRepository::class)->getAllTimeTablesForSelect())
                    ->fieldWindowView('chief-timetable::fields.timetable-window'),
            ]);
    }
}
