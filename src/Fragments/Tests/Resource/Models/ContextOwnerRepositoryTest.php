<?php

namespace Thinktomorrow\Chief\Fragments\Tests\Resource\Models;

use Thinktomorrow\Chief\Fragments\App\Actions\AttachFragment;
use Thinktomorrow\Chief\Fragments\Resource\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Resource\Models\ContextOwnerRepository;
use Thinktomorrow\Chief\Fragments\Resource\Models\ContextRepository;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class ContextOwnerRepositoryTest extends ChiefTestCase
{
    private ArticlePage $owner;

    public function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setUpAndCreateArticle();
        chiefRegister()->fragment(Quote::class);
    }

    public function test_it_returns_empty_collection()
    {
        $this->assertCount(0, app(ContextOwnerRepository::class)->getOwnersByFragment('xxx'));
    }

    public function test_it_can_get_all_owners()
    {
        $context = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'nl');
        $fragment = $this->createAndAttachFragment(Quote::resourceKey(), $context->id);

        $this->assertCount(1, app(ContextOwnerRepository::class)->getOwnersByFragment($fragment->getFragmentId()));
    }

    public function test_it_can_get_all_owners_of_multiple_contexts()
    {
        $owner2 = ArticlePage::create([]);

        $context = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'nl');
        $context2 = ContextModel::create(['owner_type' => $owner2->getMorphClass(), 'owner_id' => $owner2->id, 'locale' => 'nl']);

        // Attach to two contexts
        $fragment = $this->createAndAttachFragment(Quote::resourceKey(), $context->id);
        app(AttachFragment::class)->handle($context2->id, $fragment->getFragmentId(), 1, []);

        $this->assertCount(2, app(ContextOwnerRepository::class)->getOwnersByFragment($fragment->getFragmentId()));
    }

    public function test_when_getting_owners_it_ignores_same_owners()
    {
        $context = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'nl');
        $context2 = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'fr');

        // Attach to two contexts
        $fragment = $this->createAndAttachFragment(Quote::resourceKey(), $context->id);
        app(AttachFragment::class)->handle($context2->id, $fragment->getFragmentId(), 1, []);

        $this->assertCount(1, app(ContextOwnerRepository::class)->getOwnersByFragment($fragment->getFragmentId()));
    }

}
