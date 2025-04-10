<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\ContextActions;

use Thinktomorrow\Chief\Fragments\App\ContextActions\ContextApplication;
use Thinktomorrow\Chief\Fragments\App\ContextActions\CreateContext;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class CreateContextTest extends ChiefTestCase
{
    public function test_it_can_create_a_context()
    {
        $owner = $this->setUpAndCreateArticle();
        $command = new CreateContext($owner->modelReference(), ['nl', 'en'], ['default'], 'my title');

        $id = app(ContextApplication::class)->create($command);

        $this->assertDatabaseHas('contexts', [
            'id' => $id,
            'title' => 'my title',
            'owner_type' => $owner->modelReference()->shortClassName(),
            'owner_id' => $owner->id,
            'locales' => json_encode(['nl', 'en']),
            'active_sites' => json_encode(['default']),
        ]);
    }

    public function test_it_can_sync_active_sites_on_create(): void
    {
        $owner = $this->setUpAndCreateArticle();
        $context2 = FragmentTestHelpers::createContext($owner, [], ['default']);

        $command = new CreateContext($owner->modelReference(), ['nl', 'en'], ['default'], 'my title');

        $id = app(ContextApplication::class)->create($command);

        $this->assertDatabaseHas('contexts', [
            'id' => $id,
            'active_sites' => json_encode(['default']),
        ]);

        $this->assertDatabaseHas('contexts', [
            'id' => $context2->id,
            'active_sites' => json_encode([]),
        ]);
    }
}
