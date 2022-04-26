<?php

namespace Thinktomorrow\Chief\Tests\Application\Site\Menu;

use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Site\Menu\ChiefMenuFactory;
use Thinktomorrow\Chief\Site\Menu\MenuItem;
use Thinktomorrow\Chief\Site\Menu\MenuItemStatus;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class MenuForSiteTest extends ChiefTestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        ChiefMenuFactory::clearLoaded();

        parent::tearDown();
    }

    /** @test */
    public function it_can_render_for_site()
    {
        $page = $this->setupAndCreateArticle(['custom' => 'artikel titel', 'current_state' => PageState::PUBLISHED]);

        $this->asAdmin()
            ->post(route('chief.back.menuitem.store'), [
                'menu_type' => 'main',
                'type' => 'internal',
                'owner_reference' => $page->modelReference()->getShort(),
                'trans' => [],
            ])->assertSessionHasNoErrors();

        $item = MenuItem::create([
            'label' => ['nl' => 'second item'],
            'type' => 'custom',
            'url' => ['nl' => 'https://google.com'],
        ]);

        $collection = chiefmenu('main', 'nl');

        $this->assertCount(2, $collection);
    }

    /** @test */
    public function rendering_for_site_does_not_include_offline_items()
    {
        MenuItem::create([
            'label' => ['nl' => 'first item'],
            'type' => 'custom',
            'status' => MenuItemStatus::offline->value,
            'url' => ['nl' => 'https://google.com'],
        ]);

        MenuItem::create([
            'label' => ['nl' => 'second item'],
            'type' => 'custom',
            'url' => ['nl' => 'https://google.com'],
        ]);

        $collection = chiefmenu('main', 'nl');

        $this->assertCount(1, $collection);
    }
}
