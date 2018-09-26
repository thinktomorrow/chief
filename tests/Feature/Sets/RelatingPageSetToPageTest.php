<?php

namespace Thinktomorrow\Chief\Tests\Feature\Sets;

use Thinktomorrow\Chief\Sets\SetReference;
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

        $this->app['config']->set('thinktomorrow.chief.sets', [
            'foobar'   => [
                'action' => DummySetRepository::class.'@all',
                'parameters' => [2],
            ],
        ]);

        $this->page = ArticlePageFake::create(['collection' => 'articles']);
    }

    /** @test */
    public function a_page_can_keep_a_pageset_relation()
    {
        $stored_pageset_ref = (new SetReference('key', DummySetRepository::class.'@all', [5]))->store();

        $this->page->adoptChild($stored_pageset_ref, ['sort' => 0]);

        $this->assertCount(1, $this->page->children());
    }

    /** @test */
    public function it_displays_pageset()
    {
        $stored_pageset_ref = (new SetReference('foobar', DummySetRepository::class.'@all', [5]))->store();
        $this->page->adoptChild($stored_pageset_ref, ['sort' => 0]);

        // Empty string by default
        $this->assertEquals('', $this->page->renderChildren());
    }
}
