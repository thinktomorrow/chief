<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Queries;

use Thinktomorrow\Chief\Fragments\App\Queries\ComposeLivewireDto;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class GetOwningModelsTest extends ChiefTestCase
{
    private ArticlePage $owner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setUpAndCreateArticle();
        chiefRegister()->fragment(Quote::class);
    }

    public function test_it_can_retrieve_all_owning_resources()
    {
        $context = FragmentTestHelpers::findOrCreateContext($this->owner);
        $fragment = FragmentTestHelpers::createAndAttachFragment(Quote::class, $context->id);

        // Attach fragment to two contexts
        $owner2 = ArticlePage::create([]);
        $context2 = FragmentTestHelpers::createContext($owner2);
        FragmentTestHelpers::attachFragment($context2->id, $fragment->getFragmentId());

        $owners = app(ComposeLivewireDto::class)->getSharedFragmentDtos($fragment->getFragmentId());

        $this->assertCount(2, $owners);

        foreach ($owners as $owner) {
            $this->assertInstanceOf(ArticlePage::class, $owner['model']);
            $this->assertInstanceOf(Manager::class, $owner['manager']);
        }
    }

    public function test_it_can_get_count_of_different_owners()
    {
        $context = FragmentTestHelpers::findOrCreateContext($this->owner);
        $fragment = FragmentTestHelpers::createAndAttachFragment(Quote::class, $context->id);

        // Attach fragment to two contexts
        $owner2 = ArticlePage::create([]);
        $context2 = FragmentTestHelpers::createContext($owner2);
        FragmentTestHelpers::attachFragment($context2->id, $fragment->getFragmentId());

        $this->assertEquals(2, app(ComposeLivewireDto::class)->getCount($fragment->getFragmentId()));
    }

    public function test_when_getting_count_of_owners_it_ignores_same_owner_with_multiple_contexts()
    {
        $context = FragmentTestHelpers::findOrCreateContext($this->owner);
        $context2 = FragmentTestHelpers::createContext($this->owner);

        $fragment = FragmentTestHelpers::createAndAttachFragment(Quote::class, $context->id);
        FragmentTestHelpers::attachFragment($context2->id, $fragment->getFragmentId());

        $this->assertEquals(1, app(ComposeLivewireDto::class)->getCount($fragment->getFragmentId()));
    }
}
