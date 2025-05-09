<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Models\Page;
use Thinktomorrow\Chief\Resource\PageResource;
use Thinktomorrow\Chief\Resource\PageResourceDefault;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Actions\NestableFormPresets;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Nestable;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\NestableDefault;

class NestableArticlePage extends ArticlePage implements Nestable, Page, PageResource
{
    use NestableDefault;
    use PageResourceDefault;

    //    use NestablePageDefault;

    public static function migrateUp()
    {
        Schema::create('article_pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->index()->nullable();
            $table->string('title')->nullable();
            $table->json('values')->nullable();
            $table->string('current_state')->default('draft');
            $table->tinyInteger('order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function fields($model): iterable
    {
        yield Text::make('title');
        yield NestableFormPresets::parentSelect($model);
    }
}
