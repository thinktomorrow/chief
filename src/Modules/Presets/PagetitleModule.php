<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Modules\Presets;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\ManagedModels\Assistants\ModuleDefaults;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\HtmlField;
use Thinktomorrow\Chief\ManagedModels\Presets\Fragment;
use Thinktomorrow\Chief\Shared\Concerns\Morphable\Morphable;
use Thinktomorrow\Chief\Shared\Concerns\Morphable\MorphableContract;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\Viewable;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;

class PagetitleModule extends Model implements Fragment, MorphableContract
{
    use ModuleDefaults;

    use HasDynamicAttributes;
    use Morphable;
    use Viewable;
    use SoftDeletes;

    protected $dynamicKeys = ['content'];

    public $table = "modules";
    protected $guarded = [];

    public function fields(): Fields
    {
        return new Fields([
            HtmlField::make('content')
                ->locales()
                ->label('Pagina titel'),
        ]);
    }

    public function setFragmentModel(FragmentModel $fragmentModel): \Thinktomorrow\Chief\Fragments\Fragmentable
    {
        // TODO: Implement setFragmentModel() method.
    }
}
