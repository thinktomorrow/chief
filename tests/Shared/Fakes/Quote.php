<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes;

use Illuminate\Database\Eloquent\SoftDeletes;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\AssetLibrary\InteractsWithAssets;
use Thinktomorrow\Chief\Forms\App\Queries\Fields;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Fragments\BaseFragment;
use Thinktomorrow\Chief\Fragments\Fragment;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;

class Quote extends BaseFragment implements Fragment, HasAsset
{
    use HasDynamicAttributes;
    use InteractsWithAssets;
    use SoftDeletes;

    public function fields($model): iterable
    {
        yield Fields\Text::make('title')->rules('min:4');
        yield Fields\Text::make('title_trans')->locales(['nl', 'en']);
        yield Fields\Text::make('custom')
            ->required()
            ->validationMessages(['required' => 'custom error for :attribute'])
            ->validationAttribute('custom attribute')
            ->rules('min:4');

        yield File::make('thumb');
    }

    public function viewKey(): string
    {
        return 'quote';
    }

    protected function getDynamicLocales(): array
    {
        return ChiefSites::locales();
    }
}
