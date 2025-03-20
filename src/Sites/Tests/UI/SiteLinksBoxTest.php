<?php

namespace Thinktomorrow\Chief\Sites\Tests\UI;

use Livewire\Livewire;
use Thinktomorrow\Chief\Site\Urls\Application\SaveUrlSlugs;
use Thinktomorrow\Chief\Sites\UI\Livewire\SiteLinks\SiteLinks;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageResource;

class SiteLinksBoxTest extends ChiefTestCase
{
    public function test_it_can_create_component()
    {
        $model = $this->setUpAndCreateArticle();

        $instance = Livewire::test(SiteLinks::class, [
            'resourceKey' => ArticlePageResource::resourceKey(),
            'model' => $model,
        ]);

        $instance->assertSuccessful();
    }

    public function test_it_can_get_site_links()
    {
        $model = $this->setUpAndCreateArticle(['sites' => ['nl', 'fr']]);
        (new SaveUrlSlugs)->handle($model, ['nl' => 'test-nl', 'fr' => 'test-fr']);

        $instance = Livewire::test(SiteLinks::class, [
            'resourceKey' => ArticlePageResource::resourceKey(),
            'model' => $model,
        ]);

        // Assert see in view...
        $instance->call('getSiteLinks');

    }
}
