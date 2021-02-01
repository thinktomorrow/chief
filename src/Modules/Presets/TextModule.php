<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Modules\Presets;

use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thinktomorrow\Chief\ManagedModels\Presets\Fragment;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\HtmlField;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\Viewable;
use Thinktomorrow\Chief\Shared\Concerns\Morphable\Morphable;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;
use Thinktomorrow\Chief\Shared\Concerns\Morphable\MorphableContract;
use Thinktomorrow\Chief\ManagedModels\Assistants\ModuleDefaults;

class TextModule extends Model implements Fragment, MorphableContract
{
    use ModuleDefaults;

    use HasDynamicAttributes;
    use Morphable;
    use Viewable;
    use SoftDeletes;

    protected $dynamicKeys = ['content'];

    public $table = "modules";
    protected $guarded = [];
    public $viewKey = 'text';

    public function fields(): Fields
    {
        return new Fields([
            HtmlField::make('content')
                ->locales()
                ->label('inhoud'),
        ]);
    }
}
