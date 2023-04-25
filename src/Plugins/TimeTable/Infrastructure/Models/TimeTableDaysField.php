<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models;

use Thinktomorrow\Chief\Forms\Fields\Component;
use Thinktomorrow\Chief\Forms\Fields\Field;

class TimeTableDaysField extends Component implements Field
{
    protected string $view = 'chief-timetable::timetables.days-field';

}
