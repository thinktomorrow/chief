<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Resource\PageResource;
use Thinktomorrow\Chief\Resource\PageResourceDefault;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Form\NestableFormPresets;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\Nestable;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Model\NestableDefault;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Page\NestablePageDefault;

class NestableArticlePage extends ArticlePage implements PageResource, Nestable
{
    use PageResourceDefault;
    use NestableDefault;
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
