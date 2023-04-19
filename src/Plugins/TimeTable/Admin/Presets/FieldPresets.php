<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\TimeTable\Admin\Presets;

use Thinktomorrow\Chief\Forms\Fields\MultiSelect;
use Thinktomorrow\Chief\Forms\Form;
use Thinktomorrow\Chief\Plugins\TimeTable\Application\HasTimeTable\HasTimeTable;
use Thinktomorrow\Chief\Plugins\TimeTable\Application\Read\DateRead;
use Thinktomorrow\Chief\Plugins\TimeTable\Application\Read\TimeTableReadRepository;
use Thinktomorrow\Chief\Plugins\TimeTable\Application\Taggable\Taggable;
use Thinktomorrow\Chief\Plugins\TimeTable\Application\Taggable\TaggableRepository;

class FieldPresets
{
    public static function tags(HasTimeTable $model): iterable
    {
        yield Form::make('timetable')
            ->title('Tijdschema')
            ->position('aside')
            ->items([
                MultiSelect::make('timetable_id')
                    ->options(fn () => app(TimeTableReadRepository::class)->getAllTimeTablesForSelect())
            ]);
    }
}
