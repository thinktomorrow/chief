<?php

namespace Thinktomorrow\Chief\Fragments\Tests\Domain\Models;

use Thinktomorrow\Chief\Fragments\App\Actions\AttachFragment;
use Thinktomorrow\Chief\Fragments\Repositories\ContextOwnerRepository;
use Thinktomorrow\Chief\Fragments\Repositories\ContextRepository;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestAssist;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class ContextOwnerRepositoryTest extends ChiefTestCase
{
    private ArticlePage $owner;

    private ContextOwnerRepository $contextOwnerRepository;

    private ContextRepository $contextRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setUpAndCreateArticle();
        chiefRegister()->fragment(Quote::class);

        $this->contextOwnerRepository = app(ContextOwnerRepository::class);
        $this->contextRepository = app(ContextRepository::class);
    }

    public function test_it_returns_empty_collection()
    {
        $this->assertCount(0, $this->contextOwnerRepository->getOwnersByFragment('xxx'));
    }

    public function test_it_can_get_all_owners()
    {
        $context = $this->contextRepository->create($this->owner, []);
        $fragment = FragmentTestAssist::createAndAttachFragment(Quote::class, $context->id);

        $this->assertCount(1, $this->contextOwnerRepository->getOwnersByFragment($fragment->getFragmentId()));
    }

    public function test_it_can_get_all_owners_of_multiple_contexts()
    {
        $owner2 = ArticlePage::create([]);

        $context = $this->contextRepository->create($this->owner, []);
        $context2 = $this->contextRepository->create($owner2, []);

        // Attach to two contexts
        $fragment = FragmentTestAssist::createAndAttachFragment(Quote::class, $context->id);
        app(AttachFragment::class)->handle($context2->id, $fragment->getFragmentId(), 1, []);

        $this->assertCount(2, $this->contextOwnerRepository->getOwnersByFragment($fragment->getFragmentId()));
    }

    public function test_when_getting_owners_it_ignores_same_owners()
    {
        $context = $this->contextRepository->create($this->owner, []);
        $context2 = $this->contextRepository->create($this->owner, []);

        // Attach to two contexts
        $fragment = FragmentTestAssist::createAndAttachFragment(Quote::class, $context->id);
        app(AttachFragment::class)->handle($context2->id, $fragment->getFragmentId(), 1, []);

        $this->assertCount(1, $this->contextOwnerRepository->getOwnersByFragment($fragment->getFragmentId()));
    }
}
