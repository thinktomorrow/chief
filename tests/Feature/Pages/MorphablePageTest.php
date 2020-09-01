<?php

namespace Thinktomorrow\Chief\Tests\Feature\Pages;

use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Pages\Single;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\States\PageState;
use Illuminate\Database\Eloquent\Relations\Relation;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageFake;

class MorphablePageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Relation::morphMap([
            'singles' => Single::class,
            'articles' => ArticlePageFake::class,
            'others' => OtherCollectionFake::class,
        ]);
    }

    /** @test */
    public function a_page_requires_a_morphKey()
    {
        $this->expectException(\PDOException::class);

        factory(Page::class)->create(['morph_key' => null]);
    }

    /** @test */
    public function a_page_can_be_divided_by_morphKey()
    {
        factory(Page::class)->create(['morph_key' => 'articles']);

        $this->assertCount(1, ArticlePageFake::all());
        $this->assertCount(0, OtherCollectionFake::all());
        $this->assertCount(0, Single::all());

        // All queries from Page are ignoring morphKey
        $this->assertCount(1, Page::all());
        $this->assertCount(1, Page::all());
    }

    /** @test */
    public function a_page_can_be_retrieved_by_morphKey()
    {
        factory(Page::class)->create(['morph_key' => 'articles']);

        $this->assertNotNull(ArticlePageFake::first());
        $this->assertNull(OtherCollectionFake::first());
        $this->assertNull(Single::first());

        $this->assertNotNull(Page::first());
    }

    /** @test */
    public function default_page_has_the_default_statics_morphKey()
    {
        factory(Page::class)->create(['morph_key' => 'singles']);

        $this->assertNotNull(Page::first());
        $this->assertNull(ArticlePageFake::first());
        $this->assertNull(OtherCollectionFake::first());
    }

    /** @test */
    public function morphKey_scope_can_be_ignored()
    {
        factory(Page::class)->create(['morph_key' => 'articles']);

        $this->assertNotNull(Page::first());
    }

    /** @test */
    public function morphKey_scope_can_be_set_on_runtime()
    {
        factory(Page::class)->create(['morph_key' => 'articles']);

        $this->assertNotNull(Page::morphable('articles')->first());
        $this->assertNull(Page::morphable('others')->first());
        $this->assertNull(Page::morphable('singles')->first());
        $this->assertNull(Page::morphable(null)->first());
    }

    /** @test */
    public function morphKey_scope_is_default_statics()
    {
        factory(Page::class)->create();
        $this->assertNotNull(Page::morphable('singles')->first());
    }

    /** @test */
    public function it_returns_the_right_morphKey_with_the_eloquent_find_methods()
    {
        $article = ArticlePageFake::create([
            'morph_key' => 'articles',
            'title:nl' => 'title',
            'current_state' => PageState::PUBLISHED,
        ]);

        $this->assertInstanceOf(ArticlePageFake::class, Page::find($article->id));
        $this->assertInstanceOf(ArticlePageFake::class, Page::findOrFail($article->id));
    }

    /** @test */
    public function it_returns_the_right_morphKey_model()
    {
        ArticlePageFake::create([
            'morph_key' => 'articles',
            'title:nl' => 'title',
            'current_state' => PageState::PUBLISHED,
        ]);

        $this->assertInstanceOf(ArticlePageFake::class, Page::first());
    }
}

class OtherCollectionFake extends Page
{
}
