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
        $context = FragmentTestHelpers::createContext($owner, ['site-a', 'site-b'], ['site-b']);
        $context2 = FragmentTestHelpers::createContext($owner, ['site-a', 'site-b'], ['site-a']);

        $this->assertDatabaseHas('contexts', [
            'id' => $context->id,
            'allowed_sites' => json_encode(['site-a', 'site-b']),
            'active_sites' => json_encode(['site-b']),
        ]);

        $this->assertDatabaseHas('contexts', [
            'id' => $context2->id,
            'allowed_sites' => json_encode(['site-a', 'site-b']),
            'active_sites' => json_encode(['site-a']),
        ]);

        app(ContextApplication::class)->syncSites(
            new SyncSites($owner->modelReference(), ['site-b'])
        );

        $this->assertDatabaseHas('contexts', [
            'id' => $context->id,
            'allowed_sites' => json_encode(['site-b']),
            'active_sites' => json_encode(['site-b']),
        ]);

        $this->assertDatabaseHas('contexts', [
            'id' => $context2->id,
            'allowed_sites' => json_encode(['site-b']),
            'active_sites' => json_encode([]),
        ]);
    }

    public function test_it_does_remove_active_site_if_site_no_longer_allowed()
    {
        $owner = $this->setUpAndCreateArticle();
        $context = FragmentTestHelpers::createContext($owner, ['site-a', 'site-b'], ['site-a']);
        $context2 = FragmentTestHelpers::createContext($owner, ['site-a', 'site-b'], ['site-a']);

        app(ContextApplication::class)->syncSites(
            new SyncSites($owner->modelReference(), ['site-b'])
        );

        $this->assertDatabaseHas('contexts', [
            'id' => $context->id,
            'allowed_sites' => json_encode(['site-b']),
            'active_sites' => json_encode([]),
        ]);

        $this->assertDatabaseHas('contexts', [
            'id' => $context2->id,
            'allowed_sites' => json_encode(['site-b']),
            'active_sites' => json_encode([]),
        ]);
    }

    public function test_it_syncs_allowed_sites_when_only_one_context()
    {
        $owner = $this->setUpAndCreateArticle();
        $context = FragmentTestHelpers::createContext($owner, ['site-a'], ['site-a']);

        app(ContextApplication::class)->syncSites(
            new SyncSites($owner->modelReference(), ['site-a', 'site-b'])
        );

        $this->assertDatabaseHas('contexts', [
            'id' => $context->id,
            'allowed_sites' => json_encode(['site-a', 'site-b']),
            'active_sites' => json_encode(['site-a', 'site-b']),
        ]);
    }

    public function test_it_does_not_add_new_allowed_sites_to_contexts_when_more_than_one_context()
    {
        $owner = $this->setUpAndCreateArticle();
        $context = FragmentTestHelpers::createContext($owner, ['site-a'], ['site-a']);
        $context2 = FragmentTestHelpers::createContext($owner, ['site-a', 'site-b'], ['site-b']);

        app(ContextApplication::class)->syncSites(
            new SyncSites($owner->modelReference(), ['site-a', 'site-b'])
        );

        $this->assertDatabaseHas('contexts', [
            'id' => $context->id,
            'allowed_sites' => json_encode(['site-a']),
            'active_sites' => json_encode(['site-a']),
        ]);

        $this->assertDatabaseHas('contexts', [
            'id' => $context2->id,
            'allowed_sites' => json_encode(['site-a', 'site-b']),
            'active_sites' => json_encode(['site-b']),
        ]);
    }
}
