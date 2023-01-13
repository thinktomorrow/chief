<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Shared\Fakes;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Thinktomorrow\Chief\ManagedModels\States\State\State;
use Thinktomorrow\Chief\ManagedModels\States\State\StateConfig;
use Thinktomorrow\Chief\Resource\PageResource;
use Thinktomorrow\Chief\Resource\PageResourceDefault;

class ArticleWithStateAdminConfig extends ArticlePage implements PageResource
{
    use PageResourceDefault;

    public static function modelClassName(): string
    {
        return static::class;
    }

    public function getState(string $key): ?State
    {
        return ArticleState::from($this->article_state);
    }

    public function getStateConfig(string $stateKey): StateConfig
    {
        return new ArticleStateAdminConfig();
    }

    public function fields($model): iterable
    {
        return [];
    }

    public static function migrateUp()
    {
        Schema::create('article_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('article_state')->default(ArticleState::offline->getValueAsString());
            $table->string('draft_note')->nullable();
            $table->timestamps();
        });
    }
}
