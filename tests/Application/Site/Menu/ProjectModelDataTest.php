<?php

namespace Thinktomorrow\Chief\Tests\Application\Site\Menu;

use Thinktomorrow\Chief\Tests\Shared\PageFormParams;
use Thinktomorrow\Chief\ManagedModels\States\PageState;
use Thinktomorrow\Chief\Site\Menu\ChiefMenuFactory;
use Thinktomorrow\Chief\Site\Menu\MenuItem;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Vine\NodeCollection;
use Thinktomorrow\Chief\Site\Menu\Application\ProjectModelData;

class ProjectModelDataTest extends ChiefTestCase
{
    use PageFormParams;

    private ArticlePage $page;

    public function setUp(): void
    {
        parent::setUp();

        $this->page = $this->setupAndCreateArticle([
            'custom.nl' => 'artikel titel nl', // Custom is the specific column for the title
            'custom.en' => 'artikel titel en',
            'current_state' => PageState::PUBLISHED,
        ]);

        MenuItem::create([
            'menu_type' => 'main',
            'label' => ['nl' => 'label nl', 'en' => 'label en'],
            'type' => 'internal',
            'owner_type' => $this->page->getMorphClass(),
            'owner_id' => $this->page->id,
        ]);
    }

    /** @test */
    public function it_can_project_page_data()
    {
        app(ProjectModelData::class)->handleByOwner($this->page->getMorphClass(), $this->page->id);

        $collection = app(ChiefMenuFactory::class)->forAdmin('main', 'nl');

        $this->assertEquals('label nl', $collection->first()->getLabel());
        $this->assertEquals('artikel titel nl', $collection->first()->getAdminUrlLabel());
    }

    /** @test */
    public function it_can_project_page_data_for_all_locales()
    {
        app(ProjectModelData::class)->handleByOwner($this->page->getMorphClass(), $this->page->id);

        // nl
        $collection = app(ChiefMenuFactory::class)->forAdmin('main', 'nl');
        $this->assertEquals('label nl', $collection->first()->getLabel());
        $this->assertEquals('artikel titel nl', $collection->first()->getAdminUrlLabel());

        // en
        $collection = app(ChiefMenuFactory::class)->forAdmin('main', 'en');
        $this->assertEquals('label en', $collection->first()->getLabel());
        $this->assertEquals('artikel titel en', $collection->first()->getAdminUrlLabel());
    }

    /** @test */
    public function it_can_project_page_data_when_page_is_updated()
    {
        $this->asAdmin()->put($this->manager($this->page)->route('update', $this->page), $this->validUpdatePageParams([
            'custom' => 'artikel titel',
        ]));

        // nl
        $collection = app(ChiefMenuFactory::class)->forAdmin('main', 'nl');
        $this->assertEquals('label nl', $collection->first()->getLabel());
        $this->assertEquals('artikel titel', $collection->first()->getAdminUrlLabel());

        // en
        $collection = app(ChiefMenuFactory::class)->forAdmin('main', 'en');
        $this->assertEquals('label en', $collection->first()->getLabel());
        $this->assertEquals('artikel titel', $collection->first()->getAdminUrlLabel());
    }

    /** @test */
    public function it_can_project_page_data_when_url_is_updated()
    {
        $this->updateLinks($this->page, ['nl' => 'foobar-nl', 'en' => 'foobar-en']);

        // nl
        $collection = app(ChiefMenuFactory::class)->forAdmin('main', 'nl');
        $this->assertEquals('foobar-nl', $collection->first()->getUrl('nl'));
        $this->assertEquals('artikel titel nl', $collection->first()->getAdminUrlLabel());

        // en
        $collection = app(ChiefMenuFactory::class)->forAdmin('main', 'en');
        $this->assertEquals('foobar-en', $collection->first()->getUrl('en'));
        $this->assertEquals('artikel titel en', $collection->first()->getAdminUrlLabel());
    }

    /** @test */
    public function it_can_project_page_data_when_page_has_archived()
    {
        $this->asAdmin()->post($this->manager($this->page)->route('archive', $this->page));

        $collection = app(ChiefMenuFactory::class)->forAdmin('main', 'nl');
        $this->assertEquals('label nl', $collection->first()->getLabel());
        $this->assertEquals('artikel titel nl', $collection->first()->getAdminUrlLabel());
        $this->assertTrue($collection->first()->isOffline());
    }

    /** @test */
    public function it_can_project_page_data_when_page_has_published()
    {
        $this->asAdmin()->post($this->manager($this->page)->route('unpublish', $this->page));
        $this->asAdmin()->post($this->manager($this->page)->route('publish', $this->page));

        $collection = app(ChiefMenuFactory::class)->forAdmin('main', 'nl');
        $this->assertFalse($collection->first()->isOffline());
    }

    /** @test */
    public function it_can_project_page_data_when_page_is_deleted()
    {
        $this->asAdmin()->post($this->manager($this->page)->route('archive', $this->page));
        $this->asAdmin()->delete($this->manager($this->page)->route('delete', $this->page), [
            'deleteconfirmation' => 'DELETE',
        ]);

        $this->assertNull(MenuItem::first()->owner);

        $collection = app(ChiefMenuFactory::class)->forAdmin('main', 'nl');
        $this->assertEquals('label nl', $collection->first()->getLabel());
        $this->assertEquals('geen link', $collection->first()->getAdminUrlLabel());
        $this->assertEquals(null, $collection->first()->getUrl());
        $this->assertTrue($collection->first()->isOffline());
    }
}
