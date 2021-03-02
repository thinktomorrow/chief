<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\AssetLibrary\AssetTrait;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Fragments\Assistants\OwningFragments;
use Thinktomorrow\Chief\Fragments\Assistants\FragmentableDefaults;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\FileField;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField;
use Thinktomorrow\Chief\ManagedModels\Presets\Fragment;
use Thinktomorrow\Chief\ManagedModels\States\PageState;
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

    public function fields(): Fields
    {
        return new Fields([
            InputField::make('title')->validation(['min:4']),
            InputField::make('custom')->validation('required', ['custom.required' => 'custom error for :attribute'], ['custom' => 'custom attribute']),
            InputField::make('title_trans')->translatable(['nl', 'en']),
            FileField::make('thumb'),
        ]);
    }

    public static function migrateUp()
    {
        Schema::create('quotes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('current_state')->default(PageState::DRAFT);
            $table->json('values')->nullable(); // dynamic attributes
            $table->timestamps();
            $table->softDeletes();
        });
    }

    protected function dynamicLocales(): array
    {
        return config('chief.locales', []);
    }

    public function renderView(): string
    {
        return 'quote-content';
    }
}
