<?php

namespace Thinktomorrow\Chief\Tests\Application\Pages;

use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\NestableArticlePage;

class IndexPageTest extends ChiefTestCase
{
    public function test_it_can_get_models()
    {
        $page = $this->setupAndCreateArticle(['title' => 'Foobar', 'order' => 1]);
        ArticlePage::create(['title' => 'Foobar 2', 'order' => 8]);
        ArticlePage::create(['title' => 'Foobar 3', 'order' => 9]);

        $this->asAdmin()
            ->get($this->manager($page)->route('index'))
            ->assertSuccessful();
    }

    public function test_it_can_get_nestable_models()
    {
        NestableArticlePage::migrateUp();
        chiefRegister()->resource(NestableArticlePage::class);

        $page = NestableArticlePage::create(['id' => 1, 'title' => 'Foobar 2', 'order' => 8]);
        NestableArticlePage::create(['title' => 'Foobar 3', 'order' => 10]);
        NestableArticlePage::create(['title' => 'Foobar 3', 'parent_id' => '1', 'order' => 10]);

        $this->asAdmin()
            ->get($this->manager($page)->route('index'))
            ->assertSuccessful();
    }
}
