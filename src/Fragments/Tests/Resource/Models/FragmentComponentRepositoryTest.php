<?php

namespace Thinktomorrow\Chief\Fragments\Tests\Resource\Models;

use Thinktomorrow\Chief\Fragments\Resource\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Resource\Models\ContextRepository;
use Thinktomorrow\Chief\Fragments\Resource\Models\FragmentsComponentRepository;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Hero;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class FragmentComponentRepositoryTest extends ChiefTestCase
{
    private ArticlePage $owner;

    public function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setUpAndCreateArticle();
        chiefRegister()->fragment(Quote::class);
        chiefRegister()->fragment(Hero::class);
    }

    public function test_it_can_get_all_fragments()
    {
        $context = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'nl');
        $fragment = $this->createAndAttachFragment(Quote::resourceKey(), $context->id);

        $fragments = app()->makeWith(FragmentsComponentRepository::class, ['owner' => $this->owner])->getFragments('nl');

        $this->assertCount(1, $fragments);
    }

    public function test_it_can_get_all_fragments_by_locale()
    {
        $context = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'nl');
        $this->createAndAttachFragment(Quote::resourceKey(), $context->id);

        $this->assertCount(0, app()->makeWith(FragmentsComponentRepository::class, ['owner' => $this->owner])->getFragments('fr'));
        $this->assertCount(1, app()->makeWith(FragmentsComponentRepository::class, ['owner' => $this->owner])->getFragments('nl'));
    }

    public function test_it_retrieves_all_shareable_fragments_including_own()
    {
        $context = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'nl');
        $fragment = $this->createAndAttachFragment(Quote::resourceKey(), $context->id);

        $owner2 = ArticlePage::create();
        $context = ContextModel::create(['owner_type' => $owner2->getMorphClass(), 'owner_id' => $owner2->id, 'locale' => 'nl']);
        $shareableFragment = $this->createAndAttachFragment(Quote::resourceKey(), $context->id);

        $shareableFragments = app()->makeWith(FragmentsComponentRepository::class, ['owner' => $this->owner])->getShareableFragments('nl');

        $this->assertCount(2, $shareableFragments);
    }

    public function test_it_can_retrieve_only_shareable_fragments_when_they_are_allowed_fragments()
    {
        $context = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'nl');
        $fragment = $this->createAndAttachFragment(Hero::resourceKey(), $context->id);

        $shareableFragments = app()->makeWith(FragmentsComponentRepository::class, ['owner' => $this->owner])->getShareableFragments('nl');

        $this->assertCount(0, $shareableFragments);
    }

    public function test_already_selected_fragments_are_marked_with_a_flag()
    {
        $context = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'nl');
        $fragment = $this->createAndAttachFragment(Quote::resourceKey(), $context->id);

        $owner2 = ArticlePage::create();
        $context = ContextModel::create(['owner_type' => $owner2->getMorphClass(), 'owner_id' => $owner2->id, 'locale' => 'nl']);
        $shareableFragment = $this->createAndAttachFragment(Quote::resourceKey(), $context->id);

        $shareableFragments = app()->makeWith(FragmentsComponentRepository::class, ['owner' => $this->owner])->getShareableFragments('nl');

        $this->assertEquals($fragment->getFragmentId(), $shareableFragments[0]['fragment']->getFragmentId());
        $this->assertTrue($shareableFragments[0]['is_already_selected']);

        $this->assertEquals($shareableFragment->getFragmentId(), $shareableFragments[1]['fragment']->getFragmentId());
        $this->assertFalse($shareableFragments[1]['is_already_selected']);
    }
}
