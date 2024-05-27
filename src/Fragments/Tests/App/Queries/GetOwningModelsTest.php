<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Queries;

use Thinktomorrow\Chief\Fragments\Queries\GetOwningModels;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestAssist;
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
        $context = FragmentTestAssist::findOrCreateContext($this->owner);
        $fragment = FragmentTestAssist::createAndAttachFragment(Quote::class, $context->id);

        // Attach fragment to two contexts
        $owner2 = ArticlePage::create([]);
        $context2 = FragmentTestAssist::createContext($owner2);
        FragmentTestAssist::attachFragment($context2->id, $fragment->getFragmentId());

        $owners = app(GetOwningModels::class)->get($fragment->getFragmentId());

        $this->assertCount(2, $owners);

        foreach ($owners as $owner) {
            $this->assertInstanceOf(ArticlePage::class, $owner['model']);
            $this->assertInstanceOf(Manager::class, $owner['manager']);
        }
    }

    public function test_it_can_get_count_of_different_owners()
    {
        $context = FragmentTestAssist::findOrCreateContext($this->owner);
        $fragment = FragmentTestAssist::createAndAttachFragment(Quote::class, $context->id);

        // Attach fragment to two contexts
        $owner2 = ArticlePage::create([]);
        $context2 = FragmentTestAssist::createContext($owner2);
        FragmentTestAssist::attachFragment($context2->id, $fragment->getFragmentId());

        $this->assertEquals(2, app(GetOwningModels::class)->getCount($fragment->getFragmentId()));
    }

    public function test_when_getting_count_of_owners_it_ignores_same_owner_with_multiple_contexts()
    {
        $context = FragmentTestAssist::findOrCreateContext($this->owner);
        $context2 = FragmentTestAssist::createContext($this->owner);

        $fragment = FragmentTestAssist::createAndAttachFragment(Quote::class, $context->id);
        FragmentTestAssist::attachFragment($context2->id, $fragment->getFragmentId());

        $this->assertEquals(1, app(GetOwningModels::class)->getCount($fragment->getFragmentId()));
    }
}
