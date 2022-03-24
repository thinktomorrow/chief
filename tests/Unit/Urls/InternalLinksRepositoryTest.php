<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Urls;

use Thinktomorrow\Chief\Site\Urls\Links\Link;
use Thinktomorrow\Chief\Site\Urls\Links\LinkRepository;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Site\Urls\UrlStatus;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

class InternalLinksRepositoryTest extends ChiefTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();

//        Route::get('{slug}', function () {
//        })->name('pages.show');
    }

    /** @test */
    public function a_page_can_provide_an_url()
    {
        $page = ArticlePage::create();
        UrlRecord::create(['locale' => 'nl', 'slug' => 'foo', 'model_type' => $page->getMorphClass(), 'model_id' => $page->id, 'internal_label' => 'artikel 1']);
        UrlRecord::create(['locale' => 'en', 'slug' => 'bar', 'model_type' => $page->getMorphClass(), 'model_id' => $page->id,  'internal_label' => 'artikel 1']);

        $links = app(LinkRepository::class)->getOnlineLinks('nl');
        $this->assertCount(1, $links);
        $this->assertContainsOnlyInstancesOf(Link::class, $links);

        $this->assertEquals(
            new Link($page->getMorphClass(), (string) $page->id, 'artikel 1', 'nl', 'foo'),
            $links[0]
        );

        $links = app(\Thinktomorrow\Chief\Site\Urls\Links\LinkRepository::class)->getOnlineLinks('en');
        $this->assertCount(1, $links);
    }

    /** @test */
    public function it_does_not_retrieve_offline_urls()
    {
        $page = ArticlePage::create();
        UrlRecord::create(['status' => UrlStatus::offline->value, 'locale' => 'nl', 'slug' => 'foo', 'model_type' => $page->getMorphClass(), 'model_id' => $page->id, 'internal_label' => 'artikel 1']);

        $links = app(\Thinktomorrow\Chief\Site\Urls\Links\LinkRepository::class)->getOnlineLinks('nl');
        $this->assertCount(0, $links);
    }
}
