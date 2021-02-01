<?php

namespace Thinktomorrow\Chief\Tests\Unit\Fields;

use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Site\Urls\UrlRecord;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\ManagedModels\States\PageState;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\PageField;

class PageFieldTest extends ChiefTestCase
{
    private $page;

    protected function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
        $this->page = ArticlePage::create();
        UrlRecord::create(['locale' => 'nl', 'slug' => 'foo/bar', 'model_type' => $this->page->getMorphClass(), 'model_id' => $this->page->id]);
    }

    /** @test */
    public function pagefield_can_set_all_visitable_pages_as_options()
    {
        $pagefield = PageField::make('foobar')->pagesAsOptions();

        $this->assertCount(1, $pagefield->getOptions()[0]['values']);
    }

    /** @test */
    public function options_can_be_online_pages_only()
    {
        $this->page->changeStateOf(PageState::KEY, PageState::PUBLISHED);
        $this->page->save();

        $pagefield = PageField::make('foobar')->onlinePagesAsOptions();

        $this->assertCount(1, $pagefield->getOptions()[0]['values']);
    }

    /** @test */
    public function pagefield_can_exclude_a_page()
    {
        $page = ArticlePage::create();
        UrlRecord::create(['locale' => 'nl', 'slug' => 'foo', 'model_type' => $page->getMorphClass(), 'model_id' => $page->id]);

        $pagefield = PageField::make('foobar')->pagesAsOptions($this->page);

        $this->assertCount(1, $pagefield->getOptions()[0]['values']);
    }
}
