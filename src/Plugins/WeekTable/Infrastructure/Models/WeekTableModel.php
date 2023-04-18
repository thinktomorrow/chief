<?php

namespace Thinktomorrow\Chief\Plugins\WeekTable\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Forms\Fields\Common\FieldPresets;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModelDefault;

class WeekTableModel extends Model implements ReferableModel
{
    use ReferableModelDefault;

    protected $guarded = [];
    public $table = 'weektables';
    public $timestamps = false;

    public function fields($model): iterable
    {
        yield FieldPresets::pagetitle(
            Text::make('title')
                ->label('Naam van het weekschema')
                ->required()
        );
    }
}
