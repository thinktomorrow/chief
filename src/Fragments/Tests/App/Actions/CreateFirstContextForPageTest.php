<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Actions;

use Thinktomorrow\Chief\Fragments\App\Actions\CreateFirstContextForPage;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelCreated;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class CreateFirstContextForPageTest extends ChiefTestCase
{
    public function test_it_can_create_first_context_for_existing_context_owner(): void
    {
        $owner = $this->setUpAndCreateArticle();

        app(CreateFirstContextForPage::class)->handle($owner);

        $this->assertDatabaseHas('contexts', [
            'owner_type' => $owner->modelReference()->shortClassName(),
            'owner_id' => $owner->id,
            'title' => 'Inhoud',
            'allowed_sites' => json_encode(['nl', 'en']),
            'active_sites' => json_encode(['nl', 'en']),
        ]);
    }

    public function test_it_does_not_create_duplicate_first_context(): void
    {
        $owner = $this->setUpAndCreateArticle();
        $action = app(CreateFirstContextForPage::class);

        $action->handle($owner);
        $action->handle($owner);

        $this->assertEquals(1, ContextModel::query()
            ->where('owner_type', $owner->modelReference()->shortClassName())
            ->where('owner_id', $owner->id)
            ->count());
    }

    public function test_it_uses_owner_allowed_sites_for_new_first_context(): void
    {
        $owner = $this->setUpAndCreateArticle();
        $owner->setAllowedSites(['nl']);
        $owner->save();

        app(CreateFirstContextForPage::class)->handle($owner);

        $this->assertDatabaseHas('contexts', [
            'owner_type' => $owner->modelReference()->shortClassName(),
            'owner_id' => $owner->id,
            'allowed_sites' => json_encode(['nl']),
            'active_sites' => json_encode(['nl']),
        ]);
    }

    public function test_it_still_creates_first_context_on_managed_model_created_event(): void
    {
        $owner = $this->setUpAndCreateArticle();

        app(CreateFirstContextForPage::class)->onManagedModelCreated(new ManagedModelCreated($owner->modelReference()));

        $this->assertDatabaseHas('contexts', [
            'owner_type' => $owner->modelReference()->shortClassName(),
            'owner_id' => $owner->id,
            'title' => 'Inhoud',
        ]);
    }
}
