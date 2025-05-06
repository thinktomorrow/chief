<?php

namespace Thinktomorrow\Chief\Sites\Tests\UI;

use Livewire\Livewire;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;
use Thinktomorrow\Chief\Urls\App\Actions\CreateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\UrlApplication;
use Thinktomorrow\Chief\Urls\UI\Livewire\Links\Links;

class LinksComponentTest extends ChiefTestCase
{
    public function test_it_can_create_component()
    {
        $model = $this->setUpAndCreateArticle();

        $instance = Livewire::test(Links::class, [
            'resourceKey' => ArticlePageResource::resourceKey(),
            'model' => $model,
        ]);

        $instance->assertSuccessful();
    }

    public function test_it_can_get_links()
    {
        $model = $this->setUpAndCreateArticle(['allowed_sites' => ['nl', 'fr']]);
        app(UrlApplication::class)->create(new CreateUrl($model->modelReference(), 'nl', 'test-nl', 'online'));
        app(UrlApplication::class)->create(new CreateUrl($model->modelReference(), 'en', 'test-en', 'online'));

        $instance = Livewire::test(Links::class, [
            'resourceKey' => ArticlePageResource::resourceKey(),
            'model' => $model,
        ]);

        // Assert see in view...
        $instance->call('getLinks')
            ->assertSeeHtml('http://localhost/nl-base/test-nl')
            ->assertSeeHtml('http://localhost/en-base/test-en');
    }
}
