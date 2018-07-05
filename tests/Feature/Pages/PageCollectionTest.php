<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages;

use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageFake;
use Thinktomorrow\Chief\Tests\TestCase;

class PageCollectionTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->app['config']->set('thinktomorrow.chief.collections.pages', [
            'singles' => Single::class,
            'articles' => ArticlePageFake::class,
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

        $this->assertCount(1, ArticlePageFake::all());
        $this->assertCount(0, OtherCollectionFake::all());
        $this->assertCount(0, Single::all());

        // All queries from Page are ignoring collection
        $this->assertCount(1, Page::all());
        $this->assertCount(1, Page::all());
    }

    /** @test */
    public function a_page_can_be_retrieved_by_collection()
    {
        factory(Page::class)->create(['collection' => 'articles']);

        $this->assertNotNull(ArticlePageFake::first());
        $this->assertNull(OtherCollectionFake::first());
        $this->assertNull(Single::first());

        $this->assertNotNull(Page::first());
    }

    /** @test */
    public function default_page_has_the_default_statics_collection()
    {
        factory(Page::class)->create(['collection' => 'singles']);

        $this->assertNotNull(Page::first());
        $this->assertNull(ArticlePageFake::first());
        $this->assertNull(OtherCollectionFake::first());
    }

    /** @test */
    public function collection_scope_can_be_ignored()
    {
        factory(Page::class)->create(['collection' => 'articles']);

        $this->assertNotNull(Page::first());
    }

    /** @test */
    public function collection_scope_can_be_set_on_runtime()
    {
        factory(Page::class)->create(['collection' => 'articles']);

        $this->assertNotNull(Page::collection('articles')->first());
        $this->assertNull(Page::collection('others')->first());
        $this->assertNull(Page::collection('singles')->first());
        $this->assertNull(Page::collection(null)->first());
    }

    /** @test */
    public function collection_scope_is_default_statics()
    {
        factory(Page::class)->create();
        $this->assertNotNull(Page::collection('singles')->first());
    }

    /** @test */
    public function it_can_retrieve_all_available_collections()
    {
        factory(Page::class)->create(['collection' => 'articles']);
        factory(Page::class)->create(['collection' => 'others']);
        factory(Page::class)->create();

        $this->assertEquals(['singles', 'articles', 'others'], Page::availableCollections(true)->keys()->toArray());
    }

    /** @test */
    public function it_can_find_collection_published_by_slug()
    {
        ArticlePageFake::create([
            'collection' => 'articles',
            'title:nl' => 'title',
            'slug:nl' => 'foobar',
            'published' => 1
        ]);
        ArticlePageFake::create([
            'collection' => 'articles',
            'title:nl' => 'title',
            'slug:nl' => 'barfoo',
            'published' => 0
        ]);

        $this->assertNotNull(ArticlePageFake::findPublishedBySlug('foobar'));
        $this->assertNull(ArticlePageFake::findPublishedBySlug('barfoo'));
    }

    /** @test */
    public function it_returns_the_right_collection_with_the_eloquent_find_methods()
    {
        $article = ArticlePageFake::create([
            'collection' => 'articles',
            'title:nl' => 'title',
            'slug:nl' => 'foobar',
            'published' => 1
        ]);

        $this->assertInstanceOf(ArticlePageFake::class, Page::find($article->id));
        $this->assertInstanceOf(ArticlePageFake::class, Page::findOrFail($article->id));
    }

    /** @test */
    public function it_returns_the_right_collection_model_by_slug()
    {
        $this->disableExceptionHandling();

        ArticlePageFake::create([
            'collection' => 'articles',
            'title:nl' => 'title',
            'slug:nl' => 'foobar',
            'published' => 1
        ]);

        $this->assertInstanceOf(ArticlePageFake::class, Page::findBySlug('foobar'));
        $this->assertInstanceOf(ArticlePageFake::class, Page::findPublishedBySlug('foobar'));
    }
}

class OtherCollectionFake extends Page
{
}
