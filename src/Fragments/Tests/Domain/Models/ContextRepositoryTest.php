<?php

namespace Thinktomorrow\Chief\Fragments\Tests\Domain\Models;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Fragments\App\Repositories\ContextRepository;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class ContextRepositoryTest extends ChiefTestCase
{
    private ArticlePage $owner;

    private ContextRepository $contextRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setUpAndCreateArticle();
        chiefRegister()->fragment(Quote::class);

        $this->contextRepository = app(ContextRepository::class);
    }

    public function test_it_fails_when_context_is_not_found()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->assertNull($this->contextRepository->find('xxx'));
    }

    public function test_it_returns_empty_collection_if_contexts_by_owner_are_not_found()
    {
        $this->assertCount(0, $this->contextRepository->getByOwner($this->owner->modelReference()));
        $this->assertInstanceOf(Collection::class, $this->contextRepository->getByOwner($this->owner->modelReference()));
    }

    public function test_it_can_find_context_by_owner()
    {
        ContextModel::create(['owner_type' => $this->owner->getMorphClass(), 'owner_id' => $this->owner->id]);

        $foundContexts = $this->contextRepository->getByOwner($this->owner->modelReference());

        $this->assertCount(1, $foundContexts);
    }

    public function test_it_can_create_context()
    {
        $this->assertCount(0, $this->contextRepository->getByOwner($this->owner->modelReference()));

        $this->contextRepository->create($this->owner, []);

        $this->assertCount(1, $this->contextRepository->getByOwner($this->owner->modelReference()));
    }

    public function test_it_can_create_context_with_available_locales()
    {
        $this->assertCount(0, $this->contextRepository->getByOwner($this->owner->modelReference()));

        $this->contextRepository->create($this->owner, ['nl', 'fr']);

        $this->assertCount(1, $this->contextRepository->getByOwner($this->owner->modelReference()));

        $this->assertEquals(['nl', 'fr'], $this->contextRepository->getByOwner($this->owner->modelReference())->first()->locales);
    }

    public function test_it_can_get_all_contexts_of_a_fragment()
    {
        $context = $this->contextRepository->create($this->owner, []);
        $fragment = FragmentTestHelpers::createAndAttachFragment(Quote::class, $context->id);

        $contexts = $this->contextRepository->getContextsByFragment($fragment->getFragmentId());

        $this->assertCount(1, $contexts);
        $this->assertInstanceOf(Collection::class, $contexts);
    }

    public function test_it_returns_empty_collection_by_default()
    {
        $this->assertCount(0, $this->contextRepository->getContextsByFragment('xxx'));
    }
}
