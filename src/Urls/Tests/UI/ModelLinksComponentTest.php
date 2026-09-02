<?php

namespace Thinktomorrow\Chief\Urls\Tests\UI;

use Livewire\Livewire;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;
use Thinktomorrow\Chief\Urls\App\Actions\CreateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\UrlApplication;
use Thinktomorrow\Chief\Urls\App\Queries\GetBaseUrls;
use Thinktomorrow\Chief\Urls\UI\Livewire\ModelLinks;

class ModelLinksComponentTest extends ChiefTestCase
{
    public function test_it_can_create_component()
    {
        $model = $this->setUpAndCreateArticle();

        $instance = Livewire::test(ModelLinks::class, [
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

        $instance = Livewire::test(ModelLinks::class, [
            'resourceKey' => ArticlePageResource::resourceKey(),
            'model' => $model,
        ]);

        // Assert see in view...
        $instance->call('getLinks')
            ->assertSeeHtml('http://localhost/nl-base/test-nl')
            ->assertSeeHtml('http://localhost/en-base/test-en');
    }

    public function test_it_shows_an_action_when_a_link_is_missing()
    {
        $model = $this->setUpAndCreateArticle(['allowed_sites' => ['nl']]);

        Livewire::test(ModelLinks::class, [
            'resourceKey' => ArticlePageResource::resourceKey(),
            'model' => $model,
        ])
            ->assertSee('Voeg een link toe')
            ->assertSeeHtml('wire:click="edit"')
            ->call('edit')
            ->assertDispatched('open-edit-model-links');
    }

    public function test_it_resolves_base_urls_once_for_multiple_links(): void
    {
        $model = $this->setUpAndCreateArticle(['allowed_sites' => ['nl', 'en']]);
        app(UrlApplication::class)->create(new CreateUrl($model->modelReference(), 'nl', 'test-nl', 'online'));
        app(UrlApplication::class)->create(new CreateUrl($model->modelReference(), 'en', 'test-en', 'online'));

        $baseUrls = ['nl' => 'http://localhost/nl-base', 'en' => 'http://localhost/en-base'];
        $query = $this->createMock(GetBaseUrls::class);
        $query->expects($this->once())
            ->method('get')
            ->with($model)
            ->willReturn($baseUrls);

        app()->instance(GetBaseUrls::class, $query);

        $component = new ModelLinks;
        $component->mount($model);

        $links = $component->getLinks();

        $this->assertCount(2, $links);
        $this->assertSame($baseUrls, $links->first()->baseUrls);
        $this->assertSame($baseUrls, $links->last()->baseUrls);
    }
}
