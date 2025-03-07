<?php

namespace Thinktomorrow\Chief\Plugins\Tags\Domain\Model;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Forms\Fields\Common\FieldPresets;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModelDefault;

class TagGroupModel extends Model implements ReferableModel
{
    use ReferableModelDefault;

    protected $guarded = [];

    public $table = 'chief_taggroups';

    public $timestamps = false;

    public function fields($model): iterable
    {
        yield FieldPresets::pagetitle(
            Text::make('label')
                ->label('Groepsnaam')
                ->required()
                ->characterCount('20')
                ->rules('max:20')
                ->autofocus()
        );
    }
}
