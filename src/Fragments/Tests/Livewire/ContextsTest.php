<?php

namespace Thinktomorrow\Chief\Fragments\Tests\Livewire;

use Livewire\Livewire;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\UI\Livewire\Context\Contexts;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class ContextsTest extends ChiefTestCase
{
    public function test_it_silently_creates_a_first_context_when_none_exists(): void
    {
        $owner = $this->setUpAndCreateArticle();

        Livewire::test(Contexts::class, [
            'model' => $owner,
            'activeContextId' => null,
        ])->assertStatus(200);

        $this->assertEquals(1, ContextModel::query()
            ->where('owner_type', $owner->modelReference()->shortClassName())
            ->where('owner_id', $owner->id)
            ->count());
    }

    public function test_it_uses_allowed_sites_when_creating_first_context_via_contexts_component(): void
    {
        $owner = $this->setUpAndCreateArticle();
        $owner->setAllowedSites(['nl']);
        $owner->save();

        Livewire::test(Contexts::class, [
            'model' => $owner,
            'activeContextId' => null,
        ])->assertStatus(200);

        $this->assertDatabaseHas('contexts', [
            'owner_type' => $owner->modelReference()->shortClassName(),
            'owner_id' => $owner->id,
            'allowed_sites' => json_encode(['nl']),
            'active_sites' => json_encode(['nl']),
        ]);
    }
}
