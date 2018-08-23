<?php

namespace Thinktomorrow\Chief\Tests\Feature\PageSets;

use Thinktomorrow\Chief\PageSets\PageSetReference;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageFake;
use Thinktomorrow\Chief\Tests\TestCase;

class RelatingPageSetToPageTest extends TestCase
{
    private $page;

    protected function setUp()
    {
        parent::setUp();

        $this->app['config']->set('thinktomorrow.chief.collections', [
            'articles'   => ArticlePageFake::class,
        ]);

        $this->page = ArticlePageFake::create(['collection' => 'articles']);
    }

    /** @test */
    function a_page_can_keep_a_pageset_relation()
    {
        $stored_pageset_ref = (new PageSetReference('key', DummyPageSetRepository::class.'@all', [5]))->store();

        $this->page->adoptChild($stored_pageset_ref, ['sort' => 0]);

        $this->assertCount(1, $this->page->children());
    }

}
