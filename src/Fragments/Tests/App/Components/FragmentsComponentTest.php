<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Components;

use Thinktomorrow\Chief\Fragments\App\Components\Fragments;
use Thinktomorrow\Chief\Fragments\Resource\Models\ContextRepository;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Hero;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class FragmentsComponentTest extends ChiefTestCase
{
    private ArticlePage $owner;

    public function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setUpAndCreateArticle();
        chiefRegister()->fragment(Quote::class);
        chiefRegister()->fragment(Hero::class);
    }

    public function test_it_can_be_instantiated()
    {
        $context = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'nl');

        $component = app()->makeWith(Fragments::class, ['contextId' => $context->id]);

        $this->assertInstanceOf(Fragments::class, $component);
    }

    public function test_it_can_be_rendered()
    {
        $context = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'nl');
        $fragment = $this->createAndAttachFragment(Quote::resourceKey(), $context->id);

        $component = app()->makeWith(Fragments::class, ['contextId' => $context->id]);
        $this->assertStringContainsString('THIS IS QUOTE FRAGMENT', $component->render()->render());
    }
}
