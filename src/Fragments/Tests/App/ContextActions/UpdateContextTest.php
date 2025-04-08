<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\ContextActions;

use Thinktomorrow\Chief\Fragments\App\ContextActions\ContextApplication;
use Thinktomorrow\Chief\Fragments\App\ContextActions\UpdateContext;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class UpdateContextTest extends ChiefTestCase
{
    public function test_it_can_update_context_data()
    {
        $owner = $this->setUpAndCreateArticle();
        $context = FragmentTestHelpers::createContext($owner);

        $command = new UpdateContext($context->id, ['nl'], ['default'], 'Updated title');
        app(ContextApplication::class)->update($command);

        $this->assertDatabaseHas('contexts', [
            'id' => $context->id,
            'title' => 'Updated title',
            'locales' => json_encode(['nl']),
            'active_sites' => json_encode(['default']),
        ]);
    }
}
