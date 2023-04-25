<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\TimeTable\App\Presets;

use Thinktomorrow\Chief\Forms\Fields\MultiSelect;
use Thinktomorrow\Chief\Forms\Form;
use Thinktomorrow\Chief\Plugins\TimeTable\App\HasTimeTable\HasTimeTable;
use Thinktomorrow\Chief\Plugins\TimeTable\App\Read\TimeTableReadRepository;

class FieldPresets
{
    public static function tags(HasTimeTable $model): iterable
    {
        yield Form::make('timetable')
            ->title('Tijdschema')
            ->position('aside')
            ->items([
                MultiSelect::make('timetable_id')
                    ->options(fn () => app(TimeTableReadRepository::class)->getAllTimeTablesForSelect()),
            ]);
    }
}
