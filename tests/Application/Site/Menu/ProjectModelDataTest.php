<?php

namespace Thinktomorrow\Chief\Tests\Application\Site\Menu;

use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Site\Menu\Application\ProjectModelData;
use Thinktomorrow\Chief\Site\Menu\Menu;
use Thinktomorrow\Chief\Site\Menu\MenuItem;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\PageFormParams;

class ProjectModelDataTest extends ChiefTestCase
{
    use PageFormParams;

    private ArticlePage $page;

    protected function setUp(): void
    {
        parent::setUp();

        $this->page = $this->setupAndCreateArticle([
            'custom.nl' => 'artikel titel nl', // Custom is the specific column for the title
            'custom.en' => 'artikel titel en',
            'current_state' => PageState::published,
        ]);

        MenuItem::create([
            'menu_type' => 'main',
            'label' => ['nl' => 'label nl', 'en' => 'label en'],
            'type' => 'internal',
            'owner_type' => $this->page->getMorphClass(),
            'owner_id' => $this->page->id,
        ]);
    }

    public function test_it_can_project_page_data()
    {
        app(ProjectModelData::class)->handleByOwner($this->page->getMorphClass(), $this->page->id);

        $collection = Menu::tree('main');

        $this->assertEquals('label nl', $collection->first()->getLabel());
        $this->assertEquals('artikel titel nl', $collection->first()->getOwnerLabel());
    }

    public function test_it_can_project_page_data_for_all_locales()
    {
        app(ProjectModelData::class)->handleByOwner($this->page->getMorphClass(), $this->page->id);

        $collection = Menu::tree('main');
        $this->assertEquals('label nl', $collection->first()->getLabel('nl'));
        $this->assertEquals('artikel titel nl', $collection->first()->getOwnerLabel('nl'));
        $this->assertEquals('label en', $collection->first()->getLabel('en'));
        $this->assertEquals('artikel titel en', $collection->first()->getOwnerLabel('en'));
    }

    public function test_it_can_project_page_data_when_page_is_updated()
    {
        $this->asAdmin()->put($this->manager($this->page)->route('update', $this->page), $this->validUpdatePageParams([
            'custom' => 'artikel titel',
        ]));

        $collection = Menu::tree('main');
        $this->assertEquals('label nl', $collection->first()->getLabel('nl'));
        $this->assertEquals('artikel titel', $collection->first()->getOwnerLabel('nl'));
        $this->assertEquals('label en', $collection->first()->getLabel('en'));
        $this->assertEquals('artikel titel', $collection->first()->getOwnerLabel('en'));
    }

    public function test_it_can_project_page_data_when_url_is_updated()
    {
        $this->updateLinks($this->page, ['nl' => 'foobar-nl', 'en' => 'foobar-en']);

        $collection = Menu::tree('main');
        $this->assertEquals('/foobar-nl', $collection->first()->getUrl('nl'));
        $this->assertEquals('artikel titel nl', $collection->first()->getOwnerLabel('nl'));
        $this->assertEquals('/foobar-en', $collection->first()->getUrl('en'));
        $this->assertEquals('artikel titel en', $collection->first()->getOwnerLabel('en'));
    }

    public function test_it_can_project_page_data_when_page_has_archived()
    {
        $this->asAdmin()
            ->put($this->manager($this->page)->route('state-update', $this->page, PageState::KEY, 'archive'));

        $collection = Menu::tree('main');
        $this->assertEquals('label nl', $collection->first()->getLabel());
        $this->assertEquals('artikel titel nl', $collection->first()->getOwnerLabel());
        $this->assertTrue($collection->first()->isOffline());
    }

    public function test_it_can_project_page_data_when_page_has_published()
    {
        $this->asAdmin()->put($this->manager($this->page)->route('state-update', $this->page, PageState::KEY, 'unpublish'));
        $this->asAdmin()->put($this->manager($this->page)->route('state-update', $this->page, PageState::KEY, 'publish'));

        $collection = Menu::tree('main');
        $this->assertFalse($collection->first()->isOffline());
    }

    public function test_it_can_project_page_data_when_page_is_deleted()
    {
        $this->asAdmin()
            ->put($this->manager($this->page)->route('state-update', $this->page, PageState::KEY, 'archive'));

        $this->asAdmin()->put($this->manager($this->page)->route('state-update', $this->page, PageState::KEY, 'delete'));

        $this->assertNull(MenuItem::first()->owner);

        $collection = Menu::tree('main');
        $this->assertEquals('label nl', $collection->first()->getLabel());
        $this->assertEquals('artikel titel nl', $collection->first()->getOwnerLabel());
        $this->assertEquals(null, $collection->first()->getUrl());
        $this->assertTrue($collection->first()->isOffline());
    }
}
