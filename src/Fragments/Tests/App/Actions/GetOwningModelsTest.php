<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Actions;

use Thinktomorrow\Chief\Fragments\App\Actions\AttachFragment;
use Thinktomorrow\Chief\Fragments\App\Queries\GetOwningModels;
use Thinktomorrow\Chief\Fragments\Resource\Models\ContextModel;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class GetOwningModelsTest extends ChiefTestCase
{
    private ArticlePage $owner;

    public function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setUpAndCreateArticle();
        chiefRegister()->fragment(Quote::class);
    }

    public function test_it_can_retrieve_all_owning_resources()
    {
        $owner2 = ArticlePage::create([]);

        $context = ContextModel::create(['owner_type' => $this->owner->getMorphClass(), 'owner_id' => $this->owner->id, 'locale' => 'nl']);
        $context2 = ContextModel::create(['owner_type' => $owner2->getMorphClass(), 'owner_id' => $owner2->id, 'locale' => 'nl']);

        // Attach to two contexts
        $fragment = $this->createAndAttachFragment(Quote::resourceKey(), $context->id);
        app(AttachFragment::class)->handle($context2->id, $fragment->getFragmentId(), 1, []);

        $owners = app(GetOwningModels::class)->get($fragment->getFragmentId());

        $this->assertCount(2, $owners);

        foreach ($owners as $owner) {
            $this->assertInstanceOf(ArticlePage::class, $owner['model']);
            $this->assertInstanceOf(Manager::class, $owner['manager']);
        }
    }

    public function test_it_can_get_count_of_different_owners()
    {
        $owner2 = ArticlePage::create([]);

        $context = ContextModel::create(['owner_type' => $this->owner->getMorphClass(), 'owner_id' => $this->owner->id, 'locale' => 'nl']);
        $context2 = ContextModel::create(['owner_type' => $owner2->getMorphClass(), 'owner_id' => $owner2->id, 'locale' => 'nl']);

        // Attach to two contexts
        $fragment = $this->createAndAttachFragment(Quote::resourceKey(), $context->id);
        app(AttachFragment::class)->handle($context2->id, $fragment->getFragmentId(), 1, []);

        $this->assertEquals(2, app(GetOwningModels::class)->getCount($fragment->getFragmentId()));
    }

    public function test_when_getting_count_of_owners_it_ignores_same_owner_with_multiple_contexts()
    {
        $context = ContextModel::create(['owner_type' => $this->owner->getMorphClass(), 'owner_id' => $this->owner->id, 'locale' => 'nl']);
        $context2 = ContextModel::create(['owner_type' => $this->owner->getMorphClass(), 'owner_id' => $this->owner->id, 'locale' => 'nl']);

        // Attach to two contexts
        $fragment = $this->createAndAttachFragment(Quote::resourceKey(), $context->id);
        app(AttachFragment::class)->handle($context2->id, $fragment->getFragmentId(), 1, []);

        $this->assertEquals(1, app(GetOwningModels::class)->getCount($fragment->getFragmentId()));
    }
}
