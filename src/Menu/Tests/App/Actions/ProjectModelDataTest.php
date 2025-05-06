<?php

namespace Thinktomorrow\Chief\Menu\Tests\App\Actions;

use Thinktomorrow\Chief\ManagedModels\States\Actions\UpdateState;
use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Menu\App\Actions\ProjectModelData;
use Thinktomorrow\Chief\Menu\App\Queries\MenuTree;
use Thinktomorrow\Chief\Menu\Menu;
use Thinktomorrow\Chief\Menu\MenuItem;
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
            'title.nl' => 'artikel titel nl',
            'title.en' => 'artikel titel en',
            'current_state' => PageState::published,
        ]);

        $menu = Menu::create(['type' => 'main', 'allowed_sites' => ['nl', 'en']]);

        MenuItem::create([
            'menu_id' => $menu->id,
            'label' => ['nl' => 'label nl', 'en' => 'label en'],
            'type' => 'internal',
            'owner_type' => $this->page->getMorphClass(),
            'owner_id' => $this->page->id,
        ]);
    }

    public function test_it_can_project_page_data()
    {
        app(ProjectModelData::class)->handleByOwner($this->page->getMorphClass(), $this->page->id);

        $collection = MenuTree::bySite('nl', 'main');

        $this->assertEquals('label nl', $collection->first()->getLabel());
        $this->assertEquals('artikel titel nl', $collection->first()->getOwnerLabel());
    }

    public function test_it_can_project_page_data_for_all_locales()
    {
        app(ProjectModelData::class)->handleByOwner($this->page->getMorphClass(), $this->page->id);

        $collection = MenuTree::bySite('nl', 'main');
        $this->assertEquals('label nl', $collection->first()->getLabel('nl'));
        $this->assertEquals('artikel titel nl', $collection->first()->getOwnerLabel('nl'));
        $this->assertEquals('label en', $collection->first()->getLabel('en'));
        $this->assertEquals('artikel titel en', $collection->first()->getOwnerLabel('en'));
    }

    public function test_it_can_project_page_data_when_page_is_updated()
    {
        $this->disableExceptionHandling();
        $this->asAdmin()->put($this->manager($this->page)->route('update', $this->page), $this->validUpdatePageParams([
            'title' => [
                'nl' => 'aangepaste titel nl',
                'en' => 'updated title en',
            ],
        ]));
        $collection = MenuTree::bySite('nl', 'main');
        $this->assertEquals('label nl', $collection->first()->getLabel('nl'));
        $this->assertEquals('aangepaste titel nl', $collection->first()->getOwnerLabel('nl'));
        $this->assertEquals('label en', $collection->first()->getLabel('en'));
        $this->assertEquals('updated title en', $collection->first()->getOwnerLabel('en'));
    }

    public function test_it_can_project_page_data_when_url_is_updated()
    {
        $this->updateLinks($this->page, ['nl' => 'foobar-nl', 'en' => 'foobar-en']);
        $collection = MenuTree::bySite('nl', 'main');

        $this->assertEquals('/nl-base/foobar-nl', $collection->first()->getUrl('nl'));
        $this->assertEquals('artikel titel nl', $collection->first()->getOwnerLabel('nl'));
        $this->assertEquals('/en-base/foobar-en', $collection->first()->getUrl('en'));
        $this->assertEquals('artikel titel en', $collection->first()->getOwnerLabel('en'));
    }

    public function test_it_can_project_page_data_when_page_has_archived()
    {
        app(UpdateState::class)->handle('article_page', $this->page->modelReference(), 'current_state', 'archive');

        // Get via query builder so offline items are included
        $collection = MenuTree::byMenu('1');

        $this->assertEquals('label nl', $collection->first()->getLabel());
        $this->assertEquals('artikel titel nl', $collection->first()->getOwnerLabel());
        $this->assertTrue($collection->first()->isOffline());
    }

    public function test_it_can_project_page_data_when_page_has_published()
    {
        app(UpdateState::class)->handle('article_page', $this->page->modelReference(), 'current_state', 'unpublish');
        app(UpdateState::class)->handle('article_page', $this->page->modelReference(), 'current_state', 'publish');

        $collection = MenuTree::bySite('nl', 'main');
        $this->assertFalse($collection->first()->isOffline());
    }

    public function test_it_can_project_page_data_when_page_is_deleted()
    {
        app(UpdateState::class)->handle('article_page', $this->page->modelReference(), 'current_state', 'unpublish');
        app(UpdateState::class)->handle('article_page', $this->page->modelReference(), 'current_state', 'delete');

        $this->assertNull(MenuItem::first()->owner);

        // Get by menu so offline items are included
        $collection = MenuTree::byMenu('1');

        $this->assertEquals('label nl', $collection->first()->getLabel());
        $this->assertEquals('artikel titel nl', $collection->first()->getOwnerLabel());
        $this->assertEquals(null, $collection->first()->getUrl());
        $this->assertTrue($collection->first()->isOffline());
    }
}
