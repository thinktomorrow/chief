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

        $this->app['config']->set('thinktomorrow.chief.collections', [
            'statics' => Page::class,
            'articles' => ArticleFake::class,
            'others'   => OtherCollectionFake::class,
        ]);
    }

    /** @test */
    public function a_page_requires_a_collection()
    {
        $this->expectException(\PDOException::class);

        factory(Page::class)->create(['collection' => null]);
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
    public function default_page_has_the_default_statics_collection()
    {
        factory(Page::class)->create(['collection' => 'statics']);

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

    /** @test */
    public function collection_scope_can_be_set_on_runtime()
    {
        factory(Page::class)->create(['collection' => 'articles']);

        $this->assertNotNull(Page::collection('articles')->first());
        $this->assertNull(Page::collection('others')->first());
        $this->assertNull(Page::collection('statics')->first());
        $this->assertNull(Page::collection(null)->first());
    }

    /** @test */
    public function collection_scope_is_default_statics()
    {
        factory(Page::class)->create();
        $this->assertNotNull(Page::collection('statics')->first());
    }

    /** @test */
    public function it_can_retrieve_all_available_collections()
    {
        factory(Page::class)->create(['collection' => 'articles']);
        factory(Page::class)->create(['collection' => 'others']);
        factory(Page::class)->create();

        $this->assertEquals(['statics', 'articles', 'others'], Page::freshAvailableCollections()->keys()->toArray());
    }

    /** @test */
    public function it_can_find_collection_published_by_slug()
    {
        ArticleFake::create([
            'collection' => 'articles',
            'title:nl' => 'title',
            'content:nl' => 'content',
            'slug:nl' => 'foobar',
            'published' => 1
        ]);
        ArticleFake::create([
            'collection' => 'articles',
            'title:nl' => 'title',
            'content:nl' => 'content',
            'slug:nl' => 'barfoo',
            'published' => 0
        ]);

        $this->assertNotNull(ArticleFake::findPublishedBySlug('foobar'));
        $this->assertNull(ArticleFake::findPublishedBySlug('barfoo'));
    }
}

class ArticleFake extends Page
{
}

class OtherCollectionFake extends Page
{
}
