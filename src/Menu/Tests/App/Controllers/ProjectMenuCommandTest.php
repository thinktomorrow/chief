<?php

namespace Thinktomorrow\Chief\Menu\Tests\App\Controllers;

use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Menu\MenuItem;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\PageFormParams;

class ProjectMenuCommandTest extends ChiefTestCase
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
            'menu_id' => 1,
            'label' => ['nl' => 'label nl', 'en' => 'label en'],
            'type' => 'internal',
            'owner_type' => $this->page->getMorphClass(),
            'owner_id' => $this->page->id,
        ]);
    }

    public function test_it_can_project_all_menu_items()
    {
        $this->artisan('chief:project-menu')
            ->assertExitCode(0);

        $item = MenuItem::first();

        $this->assertEquals('artikel titel nl', $item->getOwnerLabel('nl'));
        $this->assertEquals('artikel titel en', $item->getOwnerLabel('en'));
    }
}
