<?php

namespace Thinktomorrow\Chief\Plugins\TimeTable\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Thinktomorrow\Chief\Forms\Fields\Common\FieldPresets;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Plugins\TimeTable\Domain\Values\Slots;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModelDefault;

class TimeTableModel extends Model implements ReferableModel
{
    use ReferableModelDefault;

    protected $guarded = [];
    public $table = 'timetables';
    public $timestamps = false;

//    public function getSlotsPerDay()
//    {
//        return $this->days->map(fn (DayModel $day) => Slots::fromMappedData($day->weekday, $day->slots));
//    }

    public function days(): HasMany
    {
        return $this->hasMany(DayModel::class, 'timetable_id');
    }

    public function exceptions(): BelongsToMany
    {
        return $this->belongsToMany(DateModel::class, 'timetable_date_pivot', 'timetable_id', 'timetable_date_id');
    }

    public function fields($model): iterable
    {
        yield FieldPresets::pagetitle(
            Text::make('label')
                ->label('Naam van het schema')
                ->description('Bijv. Openingsuren Gent, Levertijden magazijn, ...')
                ->required()
        );
    }
}
