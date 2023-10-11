<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\AssetLibrary\InteractsWithAssets;
use Thinktomorrow\Chief\Forms\Fields;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Fragments\Assistants\FragmentableDefaults;
use Thinktomorrow\Chief\Fragments\Assistants\OwningFragments;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Locale\ChiefLocaleConfig;
use Thinktomorrow\Chief\ManagedModels\Presets\Fragment;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;

class Quote extends Model implements Fragment, HasAsset, FragmentsOwner
{
    use OwningFragments;
    use HasDynamicAttributes {
        HasDynamicAttributes::dynamicLocaleFallback as standardDynamicLocaleFallback;
    }
    use FragmentableDefaults;
    use SoftDeletes;
    use InteractsWithAssets;

//    public $table = 'quotes';
//    public $guarded = [];
//    public $dynamicKeys = [
//        'title', 'custom', 'title_trans', 'content_trans',
//    ];
//
//    public static function migrateUp()
//    {
//        Schema::create('quotes', function (Blueprint $table) {
//            $table->increments('id');
//            $table->string('title')->nullable();
//            $table->string('current_state')->default(PageState::draft->getValueAsString());
//            $table->json('values')->nullable(); // dynamic attributes
//            $table->timestamps();
//            $table->softDeletes();
//        });
//    }

    public function dynamicLocaleFallback(): ?string
    {
        return $this->standardDynamicLocaleFallback();
    }

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

    protected function dynamicLocales(): array
    {
        return ChiefLocaleConfig::getLocales();
    }
}
