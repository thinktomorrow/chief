<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\TimeTable\App\Presets;

use Thinktomorrow\Chief\Forms\Fields\MultiSelect;
use Thinktomorrow\Chief\Forms\Layouts\Form;
use Thinktomorrow\Chief\Plugins\TimeTable\App\HasTimeTable;
use Thinktomorrow\Chief\Plugins\TimeTable\App\Read\TimeTableReadRepository;

class FieldPresets
{
    public static function timetable(HasTimeTable $model): Form
    {
        return Form::make('timetable')
            ->title('Openingsuren')
            ->items([
                MultiSelect::make('timetable_id')
                    ->options(fn () => app(TimeTableReadRepository::class)->getAllTimeTablesForSelect())
                    ->fieldPreviewView('chief-timetable::fields.timetable-window')
                    ->tag(['not-on-model-create', 'not-on-create']),
            ]);
    }
}
