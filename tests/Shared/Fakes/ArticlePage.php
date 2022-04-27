<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\AssetLibrary\AssetTrait;
use Thinktomorrow\Chief\ManagedModels\Assistants\PageDefaults;
use Thinktomorrow\Chief\ManagedModels\Presets\Page;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
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

    public $dynamicKeys = [
        'custom', 'title_trans', 'content_trans', 'seo_title','seo_description', 'title_sanitized', 'title_sanitized_trans',
    ];

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

    public function viewKey(): string
    {
        return 'article_page';
    }

    public static function migrateUp()
    {
        Schema::create('article_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->nullable();
            $table->string('current_state')->default(PageState::draft->getValueAsString());
            $table->json('values')->nullable(); // dynamic attributes
            $table->unsignedInteger('order')->default(0);
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
}
