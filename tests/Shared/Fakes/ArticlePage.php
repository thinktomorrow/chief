<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes;

use parallel\Events\Input;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\AssetLibrary\AssetTrait;
use Thinktomorrow\Chief\ManagedModels\Assistants\PageDefaults;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\FileField;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\ImageField;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField;
use Thinktomorrow\Chief\ManagedModels\Presets\Page;
use Thinktomorrow\Chief\ManagedModels\States\PageState;
use Thinktomorrow\Chief\Shared\Concerns\HasPeriod\HasPeriodTrait;
use Thinktomorrow\Chief\Shared\Concerns\Sortable;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class ArticlePage extends Model implements Page
{
    const FILEFIELD_DISK_KEY = 'file-on-other-disk';
    const IMAGEFIELD_DISK_KEY = 'image-on-other-disk';

    use PageDefaults;
    use SoftDeletes;
    use AssetTrait;
    use HasPeriodTrait;
    use Sortable;

    public $table = 'article_pages';
    public $guarded = [];

    public function fields(): Fields
    {
        return new Fields([
            InputField::make('title')->validation(['min:4']),
            InputField::make('custom')->validation('required', ['custom.required' => 'custom error for :attribute'], ['custom' => 'custom attribute']),
            InputField::make('title_trans')->locales(['nl', 'en']),
            InputField::make('content_trans')->locales(['nl', 'en'])->validation('requiredFallbackLocale'),

            FileField::make('thumb')->tag('edit'),
            FileField::make('thumb_trans')->locales(['nl', 'en'])->tag('edit'),
            FileField::make(static::FILEFIELD_DISK_KEY)->storageDisk('secondMediaDisk')->tag('edit'),
            ImageField::make('thumb_image')->tag('edit'),
            ImageField::make('thumb_image_trans')->locales(['nl', 'en'])->tag('edit'),
            ImageField::make(static::IMAGEFIELD_DISK_KEY)->storageDisk('secondMediaDisk')->tag('edit'),

            InputField::make('title_sanitized')->sanitize(function($value, array $input){
                if($value) return $value;
                if(isset($input['title'])) return Str::slug($input['title']);
                return null;
            }),
            InputField::make('title_sanitized_trans')->locales()->sanitize(function($value, array $input, $locale = null){
                if($value) return $value;
                if(isset($input['title'])) return Str::slug($input['title']) . '-' . $locale;
                return null;
            }),
        ]);
    }

    public static function migrateUp()
    {
        Schema::create('article_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('current_state')->default(PageState::DRAFT);
            $table->json('values')->nullable(); // dynamic attributes
            $table->unsignedInteger('order')->default(0);
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    protected function dynamicLocales(): array
    {
        return config('chief.locales', []);
    }

    public function allowedFragments(): array
    {
        return [
            Quote::class,
            SnippetStub::class,
        ];
    }
}
