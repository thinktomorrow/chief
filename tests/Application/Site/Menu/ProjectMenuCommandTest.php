<?php

namespace Thinktomorrow\Chief\Tests\Application\Site\Menu;

use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;
use Thinktomorrow\Chief\Site\Menu\MenuItem;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\PageFormParams;

class ProjectMenuCommandTest extends ChiefTestCase
{
    use PageFormParams;

    private ArticlePage $page;

    public function setUp(): void
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

    /** @test */
    public function it_can_project_all_menu_items()
    {
        $this->artisan('chief:project-menu')
            ->assertExitCode(0);

        $item = MenuItem::first();

        $this->assertEquals('artikel titel nl', $item->getAdminUrlLabel('nl'));
        $this->assertEquals('artikel titel en', $item->getAdminUrlLabel('en'));
    }
}
