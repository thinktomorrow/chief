<?php

namespace Thinktomorrow\Chief\Sites\Tests;

use Thinktomorrow\Chief\Tests\ChiefTestCase;

class LocalizedModelTest extends ChiefTestCase
{
    public function test_it_can_set_locales()
    {
        $model = $this->setUpAndCreateArticle();

        $instance = Livewire::test(SiteLinks::class, [
            'resourceKey' => ArticlePageResource::resourceKey(),
            'model' => $model,
        ]);

        $instance->assertSuccessful();
    }
}
