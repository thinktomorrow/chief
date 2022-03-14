<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Thinktomorrow\AssetLibrary\AssetTrait;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Fields\Image;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Fields\Validation\Rules\FallbackLocaleRequiredRule;
use Thinktomorrow\Chief\Forms\Form;
use Thinktomorrow\Chief\ManagedModels\Assistants\PageDefaults;
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

    public function fields(): iterable
    {
        yield Text::make('title')->required()->rules(['min:4']);
        yield Text::make('custom')
            ->rules(['required'])
            ->validationAttribute('custom attribute')
            ->validationMessages(['custom.required' => 'custom error for :attribute']);
        yield Text::make('title_trans')->locales(['nl', 'en']);
        yield Text::make('content_trans')->locales(['nl', 'en'])->rules(FallbackLocaleRequiredRule::RULE);

        yield File::make('thumb')->tag('edit');
        yield File::make('thumb_trans')->locales(['nl', 'en'])->tag('edit');
        yield File::make(static::FILEFIELD_DISK_KEY)->storageDisk('secondMediaDisk')->tag('edit');
        yield Image::make('thumb_image')->tag('edit');
        yield Image::make('thumb_image_trans')->locales(['nl', 'en'])->tag('edit');
        yield Image::make(static::IMAGEFIELD_DISK_KEY)->storageDisk('secondMediaDisk')->tag('edit');

        yield Text::make('title_sanitized')->prepare(function ($value, array $input) {
            if ($value) {
                return $value;
            }
            if (isset($input['title'])) {
                return Str::slug($input['title']);
            }

            return null;
        });

        yield Text::make('title_sanitized_trans')->locales()->prepare(function ($value, array $input, $locale = null) {
            if ($value) {
                return $value;
            }
            if (isset($input['title'])) {
                return Str::slug($input['title']) . '-' . $locale;
            }

            return null;
        });

        yield Form::make('seo')->items([
            Text::make('seo_title'),
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
