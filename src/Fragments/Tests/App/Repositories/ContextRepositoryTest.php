<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Repositories;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Fragments\App\Repositories\ContextRepository;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Hero;

class ContextRepositoryTest extends ChiefTestCase
{
    private ArticlePage $owner;

    private ContextRepository $contextRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setUpAndCreateArticle();
        chiefRegister()->fragment(Hero::class);

        $this->contextRepository = app(ContextRepository::class);
    }

    public function test_it_throws_exception_when_context_is_not_found()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->contextRepository->find('non-existing-id');
    }

    public function test_it_returns_empty_collection_if_no_contexts_found_for_owner()
    {
        $contexts = $this->contextRepository->getByOwner($this->owner->modelReference());
        $this->assertInstanceOf(Collection::class, $contexts);
        $this->assertCount(0, $contexts);
    }

    public function test_it_can_find_contexts_by_owner()
    {
        ContextModel::create([
            'owner_type' => $this->owner->getMorphClass(),
            'owner_id' => $this->owner->id,
        ]);

        $contexts = $this->contextRepository->getByOwner($this->owner->modelReference());
        $this->assertCount(1, $contexts);
    }

    public function test_it_can_create_context()
    {
        $this->contextRepository->create($this->owner->modelReference(), []);
        $this->assertCount(1, $this->contextRepository->getByOwner($this->owner->modelReference()));
    }

    public function test_it_can_create_context_with_locales()
    {
        $this->contextRepository->create($this->owner->modelReference(), ['nl', 'fr']);
        $locales = $this->contextRepository->getByOwner($this->owner->modelReference())->first()->locales;

        $this->assertEquals(['nl', 'fr'], $locales);
    }

    public function test_it_can_find_context_by_site()
    {
        $site = 'foo-site';

        ContextModel::create([
            'owner_type' => $this->owner->getMorphClass(),
            'owner_id' => $this->owner->id,
            'active_sites' => [$site],
        ]);

        $context = $this->contextRepository->findBySite($this->owner->modelReference(), $site);
        $this->assertNotNull($context);
        $this->assertTrue(in_array($site, $context->active_sites));
    }

    public function test_it_returns_null_if_context_by_site_does_not_exist()
    {
        $context = $this->contextRepository->findBySite($this->owner->modelReference(), 'unknown-site');
        $this->assertNull($context);
    }

    public function test_it_can_find_context_by_id()
    {
        $context = $this->contextRepository->create($this->owner->modelReference(), []);
        $found = $this->contextRepository->find($context->id);

        $this->assertEquals($context->id, $found->id);
    }

    public function test_it_can_get_default_context_id()
    {
        $context1 = $this->contextRepository->create($this->owner->modelReference(), []);
        $context2 = $this->contextRepository->create($this->owner->modelReference(), []);
        $defaultId = $this->contextRepository->getDefaultContextId($this->owner->modelReference());

        $this->assertEquals($context1->id, $defaultId);
    }

    public function test_it_returns_null_for_default_context_id_if_none_exist()
    {
        $defaultId = $this->contextRepository->getDefaultContextId($this->owner->modelReference());
        $this->assertNull($defaultId);
    }

    public function test_it_can_get_all_contexts_for_a_fragment()
    {
        $context = $this->contextRepository->create($this->owner->modelReference(), []);
        $fragment = FragmentTestHelpers::createAndAttachFragment(Hero::class, $context->id, null, 0, ['custom' => 'foobar']);

        $contexts = $this->contextRepository->getContextsByFragment($fragment->getFragmentId());

        $this->assertCount(1, $contexts);
        $this->assertInstanceOf(Collection::class, $contexts);
    }

    public function test_it_returns_empty_collection_for_unknown_fragment()
    {
        $this->assertCount(0, $this->contextRepository->getContextsByFragment('non-existent'));
    }

    public function test_it_can_count_contexts()
    {
        $this->contextRepository->create($this->owner->modelReference(), []);
        $this->contextRepository->create($this->owner->modelReference(), []);

        $count = $this->contextRepository->countContexts($this->owner->modelReference());

        $this->assertEquals(2, $count);
    }

    public function test_it_can_count_fragments_for_a_given_fragment_id()
    {
        $context = $this->contextRepository->create($this->owner->modelReference(), []);
        $fragment = FragmentTestHelpers::createAndAttachFragment(Hero::class, $context->id, null, 0, ['custom' => 'foobar']);

        $count = $this->contextRepository->countFragments($fragment->getFragmentId());

        $this->assertEquals(1, $count);
    }

    public function test_it_returns_zero_when_counting_nonexistent_fragment()
    {
        $count = $this->contextRepository->countFragments('non-existent-id');
        $this->assertEquals(0, $count);
    }
}
