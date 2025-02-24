<?php

namespace Thinktomorrow\Chief\Fragments\Tests\Domain\Models;

use Thinktomorrow\Chief\Fragments\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class FragmentRepositoryTest extends ChiefTestCase
{
    private ArticlePage $owner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setUpAndCreateArticle();
        chiefRegister()->fragment(Quote::class);
    }

    public function test_it_returns_empty_collection_by_default()
    {
        $context = FragmentTestHelpers::createContext($this->owner);
        $this->assertCount(0, app(FragmentRepository::class)->getByContext($context->id));
    }

    public function test_it_can_get_fragments_by_context()
    {
        $context = FragmentTestHelpers::createContext($this->owner);
        FragmentTestHelpers::createAndAttachFragment(Quote::class, $context->id);

        $this->assertCount(1, app(FragmentRepository::class)->getByContext($context->id));
    }

    public function test_it_cannot_get_fragments_by_other_context()
    {
        $context = FragmentTestHelpers::createContext($this->owner);
        $context2 = FragmentTestHelpers::createContext($this->owner);
        FragmentTestHelpers::createAndAttachFragment(Quote::class, $context->id);

        $this->assertCount(1, app(FragmentRepository::class)->getByContext($context->id));
        $this->assertCount(0, app(FragmentRepository::class)->getByContext($context2->id));
    }
}
