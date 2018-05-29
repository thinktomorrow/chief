<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages;

use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Tests\TestCase;

class PageCollectionTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->setUpDefaultAuthorization();
    }

    /** @test */
    public function a_page_can_be_divided_by_collection()
    {
        factory(Page::class)->create(['collection' => 'articles']);

        $this->assertCount(1, ArticleFake::all());
        $this->assertCount(0, OtherCollectionFake::all());
        $this->assertCount(0, Page::all());
    }

    /** @test */
    public function a_page_can_be_retrieved_by_collection()
    {
        factory(Page::class)->create(['collection' => 'articles']);

        $this->assertNotNull(ArticleFake::first());
        $this->assertNull(OtherCollectionFake::first());
        $this->assertNull(Page::first());
    }

    /** @test */
    public function a_page_without_collection_is_considered_as_the_default_static_pages_collection()
    {
        factory(Page::class)->create(['collection' => null]);

        $this->assertNotNull(Page::first());
        $this->assertNull(ArticleFake::first());
        $this->assertNull(OtherCollectionFake::first());
    }

    /** @test */
    public function collection_scope_can_be_ignored()
    {
        factory(Page::class)->create(['collection' => 'articles']);

        $this->assertNotNull(Page::ignoreCollection()->first());
    }
}

class ArticleFake extends Page
{
    public $collection = 'articles';
    public $table = 'pages';
}

class OtherCollectionFake extends Page
{
    public $collection = 'others';
    public $table = 'pages';
}