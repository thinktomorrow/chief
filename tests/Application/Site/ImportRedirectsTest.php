<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Site;

use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Site\Redirects\AddRedirect;
use Thinktomorrow\Chief\Site\Redirects\RedirectUrlAlreadyExists;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Site\Urls\UrlRecordNotFound;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

final class ImportRedirectsTest extends ChiefTestCase
{
    private AddRedirect $addRedirect;

    protected function setUp(): void
    {
        parent::setUp();

        $this->addRedirect = app(AddRedirect::class);
    }

    /** @test */
    public function it_can_add_redirect()
    {
        $article = $this->setupAndCreateArticle(['title' => 'foobar', 'current_state' => PageState::published]);
        $this->updateLinks($article, ['nl' => 'foobar-nl', 'en' => 'foobar-en']);

        $this->assertNull(UrlRecord::findRecentRedirect($article, 'nl'));

        $this->addRedirect->handle('nl', '/foobar-redirect-nl', '/foobar-nl');
        $this->assertEquals('foobar-redirect-nl', UrlRecord::findRecentRedirect($article, 'nl')->slug);

        $this->addRedirect->handle('en', '/foobar-redirect-en', '/foobar-en');
        $this->assertEquals('foobar-redirect-en', UrlRecord::findRecentRedirect($article, 'en')->slug);
    }

    /** @test */
    public function it_can_add_redirect_without_slash()
    {
        $article = $this->setupAndCreateArticle(['title' => 'foobar', 'current_state' => PageState::published]);
        $this->updateLinks($article, ['nl' => 'foobar-nl', 'en' => 'foobar-en']);

        $this->assertNull(UrlRecord::findRecentRedirect($article, 'nl'));

        $this->addRedirect->handle('nl', 'foobar-redirect-nl', 'foobar-nl');
        $this->assertEquals('foobar-redirect-nl', UrlRecord::findRecentRedirect($article, 'nl')->slug);
    }

    /** @test */
    public function redirect_is_stripped_from_host()
    {
        $article = $this->setupAndCreateArticle(['title' => 'foobar', 'current_state' => PageState::published]);
        $this->updateLinks($article, ['nl' => 'foobar-nl', 'en' => 'foobar-en']);

        $this->assertNull(UrlRecord::findRecentRedirect($article, 'nl'));

        $this->addRedirect->handle('nl', 'http://other.com/foobar-redirect-nl', 'foobar-nl');
        $this->assertEquals('foobar-redirect-nl', UrlRecord::findRecentRedirect($article, 'nl')->slug);
    }

    /** @test */
    public function it_can_redirect_to_different_locale()
    {
        // Can redirect to different locale
        // Locale segment should be included in slug...
        // Makes simpler for searches and redirects...
        // Downside is that changing in locale package, should be reflected in adding redirects since now the slugs are all invalid
        $this->markTestIncomplete();
    }

    /** @test */
    public function it_should_not_import_redirect_when_url_already_exists_ignoring_slash()
    {
        $article = $this->setupAndCreateArticle(['title' => 'foobar', 'current_state' => PageState::published]);
        $this->updateLinks($article, ['nl' => 'foobar-nl', 'en' => 'foobar-en']);

        $article2 = ArticlePage::create(['title' => 'baz', 'current_state' => PageState::published]);
        $this->updateLinks($article2, ['nl' => 'baz-nl', 'en' => 'baz-en']);

        $this->expectException(RedirectUrlAlreadyExists::class);
        $this->addRedirect->handle('nl', 'baz-nl', '/foobar-nl');

        $this->assertNull(UrlRecord::findRecentRedirect($article, 'nl'));
    }

    /** @test */
    public function it_should_not_import_redirect_when_url_already_exists()
    {
        $article = $this->setupAndCreateArticle(['title' => 'foobar', 'current_state' => PageState::published]);
        $this->updateLinks($article, ['nl' => 'foobar-nl', 'en' => 'foobar-en']);

        $article2 = ArticlePage::create(['title' => 'baz', 'current_state' => PageState::published]);
        $this->updateLinks($article2, ['nl' => 'baz-nl', 'en' => 'baz-en']);

        $this->expectException(RedirectUrlAlreadyExists::class);
        $this->addRedirect->handle('nl', '/baz-nl', '/foobar-nl');

        $this->assertNull(UrlRecord::findRecentRedirect($article, 'nl'));
    }

    /** @test */
    public function it_cannot_import_redirect_when_target_url_does_not_exist()
    {
        $this->expectException(UrlRecordNotFound::class);

        $this->addRedirect->handle('nl', '/baz-nl', '/foobar-nl');

        $this->assertCount(0, UrlRecord::all());
    }

    /** @test */
    public function it_can_import_redirect_for_external_target_url()
    {
        $this->markTestIncomplete();
    }
}
