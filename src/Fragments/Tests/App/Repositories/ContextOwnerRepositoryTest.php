<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Thinktomorrow\Chief\Fragments\App\Actions\AttachRootFragment;
use Thinktomorrow\Chief\Fragments\App\Repositories\ContextOwnerRepository;
use Thinktomorrow\Chief\Fragments\App\Repositories\ContextRepository;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Hero;

class ContextOwnerRepositoryTest extends ChiefTestCase
{
    private ArticlePage $owner;

    private ContextOwnerRepository $contextOwnerRepository;

    private ContextRepository $contextRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setUpAndCreateArticle();
        chiefRegister()->fragment(Hero::class);

        $this->contextOwnerRepository = app(ContextOwnerRepository::class);
        $this->contextRepository = app(ContextRepository::class);
    }

    public function test_it_returns_empty_when_no_contexts_have_fragment()
    {
        $this->assertCount(0, $this->contextOwnerRepository->getOwnersByFragment('non-existent-id'));
    }

    public function test_it_can_get_owner_by_fragment()
    {
        $context = $this->contextRepository->create($this->owner->modelReference(), []);
        $fragment = FragmentTestHelpers::createAndAttachFragment(Hero::class, $context->id);

        $owners = $this->contextOwnerRepository->getOwnersByFragment($fragment->getFragmentId());

        $this->assertCount(1, $owners);
        $this->assertTrue($owners->contains($this->owner));
    }

    public function test_it_returns_unique_owners_across_multiple_contexts()
    {
        $owner2 = ArticlePage::create([]);
        $context1 = $this->contextRepository->create($this->owner->modelReference(), []);
        $context2 = $this->contextRepository->create($owner2->modelReference(), []);

        $fragment = FragmentTestHelpers::createAndAttachFragment(Hero::class, $context1->id);
        app(AttachRootFragment::class)->handle($context2->id, $fragment->getFragmentId(), 1, []);

        $owners = $this->contextOwnerRepository->getOwnersByFragment($fragment->getFragmentId());

        $this->assertCount(2, $owners);
        $this->assertTrue($owners->contains($this->owner));
        $this->assertTrue($owners->contains($owner2));
    }

    public function test_it_ignores_duplicate_owner_across_contexts()
    {
        $context1 = $this->contextRepository->create($this->owner->modelReference(), []);
        $context2 = $this->contextRepository->create($this->owner->modelReference(), []);

        $fragment = FragmentTestHelpers::createAndAttachFragment(Hero::class, $context1->id);
        app(AttachRootFragment::class)->handle($context2->id, $fragment->getFragmentId(), 1, []);

        $owners = $this->contextOwnerRepository->getOwnersByFragment($fragment->getFragmentId());

        $this->assertCount(1, $owners);
        $this->assertEquals($this->owner->id, $owners->first()->id);
    }

    public function test_it_can_get_all_context_owners()
    {
        $owner2 = ArticlePage::create([]);
        $this->contextRepository->create($this->owner->modelReference(), []);
        $this->contextRepository->create($owner2->modelReference(), []);

        $owners = $this->contextOwnerRepository->getAllOwners();

        $this->assertCount(2, $owners);
        $this->assertTrue($owners->contains($this->owner));
        $this->assertTrue($owners->contains($owner2));
    }

    public function test_it_skips_invalid_context_owners()
    {
        // Create fake context with null owner
        $context = ContextModel::create(['owner_type' => 'xxx', 'owner_id' => 123]);

        $this->expectExceptionMessage('Class "xxx" not found');

        $this->contextOwnerRepository->getAllOwners();
    }

    public function test_it_can_find_owner_by_context_id()
    {
        $context = $this->contextRepository->create($this->owner->modelReference(), []);
        $owner = $this->contextOwnerRepository->findOwner($context->id);

        $this->assertEquals($this->owner->id, $owner->id);
    }

    public function test_it_throws_if_context_not_found()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->contextOwnerRepository->findOwner('invalid-context-id');
    }
}
