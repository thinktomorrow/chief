<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\AssetLibrary\AssetTrait;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Fragments\Assistants\FragmentableDefaults;
use Thinktomorrow\Chief\Fragments\Assistants\OwningFragments;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\ManagedModels\Presets\Fragment;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;

class Quote extends Model implements Fragment, HasAsset, FragmentsOwner
{
    use FragmentableDefaults;
    use OwningFragments;
    use HasDynamicAttributes;
    use SoftDeletes;
    use AssetTrait;

    public $table = 'quotes';
    public $guarded = [];
    public $dynamicKeys = [
        'title', 'custom', 'title_trans', 'content_trans',
    ];

    public function fields($model): iterable
    {
        yield Fields\Text::make('title')->rules('min:4');
        yield Fields\Text::make('title_trans')->locales(['nl','en']);
        yield Fields\Text::make('custom')
            ->required()
            ->validationMessages(['required' => 'custom error for :attribute'])
            ->validationAttribute('custom attribute')
            ->rules('min:4');

        yield File::make('thumb');
    }

    public static function migrateUp()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('current_state')->default(PageState::draft->getValueAsString());
            $table->json('values')->nullable(); // dynamic attributes
            $table->timestamps();
            $table->softDeletes();
        });
    }

    protected function dynamicLocales(): array
    {
        return config('chief.locales', []);
    }

    public function viewKey(): string
    {
        return 'quote';
    }
}
