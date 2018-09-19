<?php

namespace Thinktomorrow\Chief\Tests\Unit\Common;

use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Pages\PageTranslation;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageFake;
use Thinktomorrow\Chief\Tests\Fakes\ProductPageFake;
use Thinktomorrow\Chief\Tests\TestCase;

class CollectionInstanceTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();

        $this->app['config']->set('thinktomorrow.chief.collections', [
            'articles' => ArticlePageFake::class,
            'products' => ProductPageFake::class,
            'singles'  => Single::class,
        ]);
    }

    /** @test */
    public function it_returns_expected_instance_on_create()
    {
        $instance = Page::create(['collection' => 'articles']);
        $this->assertInstanceOf(ArticlePageFake::class, $instance);

        $instance = Page::firstOrCreate(['collection' => 'articles']);
        $this->assertInstanceOf(ArticlePageFake::class, $instance);

        $instance = Page::updateOrCreate(['collection' => 'articles']);
        $this->assertInstanceOf(ArticlePageFake::class, $instance);
    }

    /** @test */
    public function it_returns_expected_instance_on_find()
    {
        $page = Page::create(['collection' => 'articles']);

        $instance = Page::find($page->id);
        $this->assertInstanceOf(ArticlePageFake::class, $instance);

        $instance = Page::findOrFail($page->id);
        $this->assertInstanceOf(ArticlePageFake::class, $instance);

        $collection = Page::findMany([$page->id]);
        $this->assertInstanceOf(ArticlePageFake::class, $collection->first());

        $instance = Page::where('id', $page->id)->first();
        $this->assertInstanceOf(ArticlePageFake::class, $instance);
    }

    /** @test */
    public function it_creates_single_collection_by_default()
    {
        $page = Page::create();

        $instance = Page::find($page->id);
        $this->assertInstanceOf(Single::class, $instance);
    }

    /** @test */
    public function it_returns_expected_instance_on_relations()
    {
        Page::create([
            'collection' => 'articles',
            'title:nl'   => 'new title',
            'slug:nl'    => 'new-slug',
        ]);

        $this->assertInstanceOf(ArticlePageFake::class, PageTranslation::first()->page()->first());
    }
}
