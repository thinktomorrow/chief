<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Queries;

use Thinktomorrow\Chief\Fragments\App\Queries\FragmentsRenderer;
use Thinktomorrow\Chief\Fragments\Domain\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestAssist;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class FragmentsRendererTest extends ChiefTestCase
{
    private ArticlePage $owner;

    public function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setUpAndCreateArticle();
        chiefRegister()->fragment(Quote::class);
    }

    public function test_it_can_render_fragments()
    {
        $context = ContextModel::create(['owner_type' => $this->owner->getMorphClass(), 'owner_id' => $this->owner->id, 'locale' => 'nl']);
        FragmentTestAssist::createAndAttachFragment(Quote::resourceKey(), $context->id);

        $this->assertEquals("THIS IS QUOTE FRAGMENT\n", app(FragmentsRenderer::class)->render($this->owner, 'nl'));
        $this->assertEquals("", app(FragmentsRenderer::class)->render($this->owner, 'fr'));
    }

    public function test_it_does_not_render_anything_by_default()
    {
        $this->assertEquals("", app(FragmentsRenderer::class)->render($this->owner, 'nl'));
    }
}
