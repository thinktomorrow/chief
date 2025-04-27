<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\ContextActions;

use Thinktomorrow\Chief\Fragments\App\ContextActions\ContextApplication;
use Thinktomorrow\Chief\Fragments\App\ContextActions\SyncSites;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class SyncContextSitesTest extends ChiefTestCase
{
    public function test_it_can_sync_context_sites()
    {
        $owner = $this->setUpAndCreateArticle();
        $context = FragmentTestHelpers::createContext($owner, ['default', 'site-b'], ['default', 'site-b']);

        $this->assertDatabaseHas('contexts', [
            'id' => $context->id,
            'allowed_sites' => json_encode(['default', 'site-b']),
            'active_sites' => json_encode(['default', 'site-b']),
        ]);

        app(ContextApplication::class)->syncSites(
            new SyncSites($owner->modelReference(), ['site-b'])
        );

        $this->assertDatabaseHas('contexts', [
            'id' => $context->id,
            'allowed_sites' => json_encode(['site-b']),
            'active_sites' => json_encode(['site-b']),
        ]);
    }

    public function test_it_does_remove_active_site_if_site_no_longer_allowed()
    {
        $owner = $this->setUpAndCreateArticle();
        $context = FragmentTestHelpers::createContext($owner, ['default', 'site-b'], ['default']);

        app(ContextApplication::class)->syncSites(
            new SyncSites($owner->modelReference(), ['site-b'])
        );

        $this->assertDatabaseHas('contexts', [
            'id' => $context->id,
            'allowed_sites' => json_encode(['site-b']),
            'active_sites' => json_encode([]),
        ]);
    }

    public function test_it_does_not_add_new_allowed_sites_to_existing_contexts()
    {
        $owner = $this->setUpAndCreateArticle();
        $context = FragmentTestHelpers::createContext($owner, ['default'], ['default']);

        app(ContextApplication::class)->syncSites(
            new SyncSites($owner->modelReference(), ['default', 'site-b'])
        );

        $this->assertDatabaseHas('contexts', [
            'id' => $context->id,
            'allowed_sites' => json_encode(['default']),
            'active_sites' => json_encode(['default']),
        ]);
    }
}
