<?php

namespace Thinktomorrow\Chief\Tests\Feature\Common\Morphables;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Pages\PageTranslation;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageFake;
use Thinktomorrow\Chief\Tests\Fakes\ProductPageFake;
use Thinktomorrow\Chief\Tests\TestCase;

class MorphableInstanceTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        Relation::morphMap([
            'articles' => ArticlePageFake::class,
        ]);

//        $this->app['config']->set('thinktomorrow.chief.collections', [
//            'articles' => ArticlePageFake::class,
//            'products' => ProductPageFake::class,
//            'singles'  => Single::class,
//        ]);
    }

    public function tearDown()
    {
        // Force empty the morphMap each time because it is kept during the entire testrun.
        Relation::$morphMap = [];

        parent::tearDown();
    }

    /** @test */
    public function it_returns_expected_instance_on_create()
    {
        $instance = Page::create(['morph_key' => ProductPageFake::class]);
        $this->assertInstanceOf(ProductPageFake::class, $instance);

        $instance = Page::firstOrCreate(['morph_key' => ProductPageFake::class]);
        $this->assertInstanceOf(ProductPageFake::class, $instance);

        $instance = Page::updateOrCreate(['morph_key' => ProductPageFake::class]);
        $this->assertInstanceOf(ProductPageFake::class, $instance);
    }

    /** @test */
    public function it_can_use_key_from_relation_morphmap()
    {
        $instance = Page::create(['morph_key' => 'articles']);
        $this->assertInstanceOf(ArticlePageFake::class, $instance);

        $instance = Page::firstOrCreate(['morph_key' => 'articles']);
        $this->assertInstanceOf(ArticlePageFake::class, $instance);

        $instance = Page::updateOrCreate(['morph_key' => 'articles']);
        $this->assertInstanceOf(ArticlePageFake::class, $instance);
    }

    /** @test */
    public function it_returns_expected_instance_on_find()
    {
        $page = Page::create(['morph_key' => 'articles']);

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
    public function it_creates_singles_morphable_by_default()
    {
        $page = Page::create();

        $instance = Page::find($page->id);
        $this->assertInstanceOf(Single::class, $instance);
    }

    /** @test */
    public function it_returns_expected_instance_on_relations()
    {
        Page::create([
            'morph_key' => 'articles',
            'title:nl'   => 'new title',
            'slug:nl'    => 'new-slug',
        ]);

        $this->assertInstanceOf(ArticlePageFake::class, PageTranslation::first()->page()->first());
    }
}
