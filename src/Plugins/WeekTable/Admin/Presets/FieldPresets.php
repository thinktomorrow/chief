<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\WeekTable\Admin\Presets;

use Thinktomorrow\Chief\Forms\Fields\MultiSelect;
use Thinktomorrow\Chief\Forms\Form;
use Thinktomorrow\Chief\Plugins\WeekTable\Application\HasWeekTable\HasWeekTable;
use Thinktomorrow\Chief\Plugins\WeekTable\Application\Read\DateRead;
use Thinktomorrow\Chief\Plugins\WeekTable\Application\Read\WeekTableReadRepository;
use Thinktomorrow\Chief\Plugins\WeekTable\Application\Taggable\Taggable;
use Thinktomorrow\Chief\Plugins\WeekTable\Application\Taggable\TaggableRepository;

class FieldPresets
{
    public static function tags(HasWeekTable $model): iterable
    {
        yield Form::make('weektable')
            ->title('Weekschema')
            ->position('aside')
            ->items([
                MultiSelect::make('weektable_id')
                    ->options(fn () => app(WeekTableReadRepository::class)->getAllWeekTablesForSelect())
            ]);
    }
}
