<?php

namespace Thinktomorrow\Chief\Fragments\Tests\Resource\Models;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Fragments\Resource\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Resource\Models\ContextRepository;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class ContextRepositoryTest extends ChiefTestCase
{
    private ArticlePage $owner;

    public function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setUpAndCreateArticle();
        chiefRegister()->fragment(Quote::class);
    }

    public function test_it_returns_null_if_context_by_owner_is_not_found()
    {
        $this->assertNull(app(ContextRepository::class)->findByOwner($this->owner, 'nl'));
    }

    public function test_it_can_find_context_by_owner()
    {
        $context = ContextModel::create(['owner_type' => $this->owner->getMorphClass(), 'owner_id' => $this->owner->id, 'locale' => 'nl']);

        $foundContext = app(ContextRepository::class)->findByOwner($this->owner, 'nl');

        $this->assertEquals($context->toArray(), $foundContext->toArray());
    }

    public function test_it_cannot_find_context_by_owner_if_locale_does_not_match()
    {
        ContextModel::create(['owner_type' => $this->owner->getMorphClass(), 'owner_id' => $this->owner->id, 'locale' => 'nl']);

        $this->assertNull(app(ContextRepository::class)->findByOwner($this->owner, 'fr'));
    }

    public function test_it_can_create_context_for_owner()
    {
        $context = app(ContextRepository::class)->createForOwner($this->owner, 'fr');

        $foundContext = app(ContextRepository::class)->findByOwner($this->owner, 'fr');
        $this->assertEquals($context->toArray(), $foundContext->toArray());
    }

    public function test_it_can_get_all_contexts_of_a_fragment()
    {
        $context = app(ContextRepository::class)->createForOwner($this->owner, 'fr');
        $fragment = $this->createAndAttachFragment(Quote::resourceKey(), $context->id);

        $contexts = app(ContextRepository::class)->getByFragment($fragment->getFragmentId());

        $this->assertInstanceOf(Collection::class, $contexts);
        $this->assertCount(1, $contexts);
    }

    public function test_it_returns_empty_collection_by_default()
    {
        $this->assertCount(0, app(ContextRepository::class)->getByFragment('xxx'));
    }

}
