<?php

namespace Thinktomorrow\Chief\Tests\Unit\Urls;

use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageWithBaseSegments;
use Thinktomorrow\Chief\Tests\Shared\PageFormParams;

class PageUrlTest extends ChiefTestCase
{
    use PageFormParams;

    private ArticlePageWithBaseSegments $model;

    public function setUp(): void
    {
        parent::setUp();

        $this->setUpChiefEnvironment();

        $this->model = $this->setupAndCreateArticleWithBaseSegments();

        // Add links for this model
        $this->updateLinks($this->model, [
            'nl' => 'foobar',
            'en' => 'foobar',
        ]);
    }

    /** @test */
    public function the_fixed_base_segment_is_prepended_to_the_slug()
    {
        app()->setLocale('nl');
        $this->assertEquals(url('/artikels/foobar'), $this->model->url());
        $this->assertEquals(url('/artikels/foobar'), $this->model->url('nl'));
        $this->assertEquals(url('/articles/foobar'), $this->model->url('en'));
    }

    /** @test */
    public function url_is_by_default_based_on_current_locale()
    {
        app()->setLocale('nl');
        $this->assertEquals(url('/artikels/foobar'), $this->model->url());

        app()->setLocale('en');
        $this->assertEquals(url('/articles/foobar'), $this->model->url());
    }

    /** @test */
    public function the_fallback_locale_for_the_base_url_segment_is_used_when_current_locale_not_found()
    {
        config()->set('app.fallback_locale', 'en');
        $this->assertEquals('articles', $this->model->baseUrlSegment('fr'));

        config()->set('app.fallback_locale', 'nl');
        $this->assertEquals('artikels', $this->model->baseUrlSegment('fr'));
    }
}
