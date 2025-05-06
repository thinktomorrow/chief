<?php

namespace Thinktomorrow\Chief\Urls\Tests;

use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePageWithBaseSegments;
use Thinktomorrow\Chief\Tests\Shared\PageFormParams;

class PageUrlTest extends ChiefTestCase
{
    use PageFormParams;

    private ArticlePageWithBaseSegments $model;

    protected function setUp(): void
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

    public function test_the_fixed_base_segment_is_prepended_to_the_slug()
    {
        app()->setLocale('nl');
        $this->assertEquals(url('/nl-base/foobar'), $this->model->url());
        $this->assertEquals(url('/nl-base/foobar'), $this->model->url('nl'));
        $this->assertEquals(url('/en-base/foobar'), $this->model->url('en'));
    }

    public function test_url_is_by_default_based_on_current_locale()
    {
        app()->setLocale('nl');
        $this->assertEquals(url('/nl-base/foobar'), $this->model->url());

        app()->setLocale('en');
        $this->assertEquals(url('/en-base/foobar'), $this->model->url());
    }
}
