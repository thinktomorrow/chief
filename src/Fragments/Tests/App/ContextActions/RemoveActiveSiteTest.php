<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\ContextActions;

use Thinktomorrow\Chief\Fragments\App\ContextActions\ContextApplication;
use Thinktomorrow\Chief\Fragments\App\ContextActions\RemoveActiveSite;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class RemoveActiveSiteTest extends ChiefTestCase
{
    public function test_it_can_remove_an_active_site()
    {
        $owner = $this->setUpAndCreateArticle();
        $context = FragmentTestHelpers::createContext($owner, [], ['default', 'site-b']);

        $this->assertDatabaseHas('contexts', [
            'id' => $context->id,
            'active_sites' => json_encode(['default', 'site-b']),
        ]);

        $command = new RemoveActiveSite($owner->modelReference(), 'site-b');
        app(ContextApplication::class)->removeActiveSite($command);

        $this->assertDatabaseHas('contexts', [
            'id' => $context->id,
            'active_sites' => json_encode(['default']),
        ]);
    }

    public function test_it_does_nothing_if_site_not_active()
    {
        $owner = $this->setUpAndCreateArticle();
        $context = FragmentTestHelpers::createContext($owner, [], ['default']);

        $command = new RemoveActiveSite($owner->modelReference(), 'non-existent-site');
        app(ContextApplication::class)->removeActiveSite($command);

        $this->assertDatabaseHas('contexts', [
            'id' => $context->id,
            'active_sites' => json_encode(['default']),
        ]);
    }
}
