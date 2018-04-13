<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Chief\Articles\Article;
use Chief\Models\Asset;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Thinktomorrow\AssetLibrary\Models\AssetUploader;

class ArticleTest extends TestCase
{
    use DatabaseTransactions, DatabaseMigrations;

    public function tearDown()
    {
        $this->beforeApplicationDestroyed(function () {
            DB::disconnect();
        });
        parent::tearDown();
    }

    /**
     * @test
     */
    public function it_can_get_all_articles_published()
    {
        factory(Article::class, 3)->create(['published' => 1]);

        $articles = Article::getAll();

        $this->assertEquals(3, $articles->count());
    }

    /**
     * @test
     */
    public function it_can_get_an_article_by_slug()
    {
        $article = factory(Article::class)->create();

        $article->updateTranslation('nl', [
            'title'     => 'blue',
            'content'   => 'blue',
            'short'     => 'blue',
            'slug'      => 'foobar',
        ]);

        $this->assertEquals($article->id, Article::findBySlug('foobar')->id);
    }

    /**
     * @test
     */
    public function it_can_get_an_article_by_slug_published()
    {
        $article = factory(Article::class)->create(['published' => 1]);
        $article2 = factory(Article::class)->create(['published' => 0]);

        $article->updateTranslation('nl', [
            'title'     => 'blue',
            'content'   => 'blue',
            'short'     => 'blue',
            'slug'      => 'foobar',
        ]);

        $article2->updateTranslation('nl', [
            'title'     => 'blue',
            'content'   => 'blue',
            'short'     => 'blue',
            'slug'      => 'foobar2',
        ]);

        $this->assertEquals($article->id, Article::findPublishedBySlug('foobar')->id);
        $this->assertNull(Article::findPublishedBySlug('foobar2'));
    }

    /**
     * @test
     */
    public function it_can_get_articles_sorted_by_published()
    {
        factory(Article::class)->create(['published' => 1]);
        factory(Article::class)->create(['published' => 0]);
        factory(Article::class)->create(['published' => 1]);


        $this->assertTrue(Article::sortedByPublished()->first()->isPublished());
        $this->assertFalse(Article::sortedByPublished()->get()->last()->isPublished());
    }

    /**
     * @test
     */
    public function it_can_get_articles_sorted_by_recent()
    {
        factory(Article::class)->create(['created_at' => Carbon::now()]);
        factory(Article::class)->create(['created_at' => Carbon::now()->subWeek()]);
        factory(Article::class)->create(['created_at' => Carbon::now()->subDay()]);


        $this->assertTrue(Article::sortedByRecent()->first()->created_at->gt(Carbon::now()->subday()));
        $this->assertTrue(Article::sortedByRecent()->get()->last()->created_at->lt(Carbon::now()->subdays(5)));
    }
}
