<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Application\Fragments;

use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;

class FragmentOwningHandlingTest extends ChiefTestCase
{
    private ArticlePage $owner;
    private Quote $fragment;

    public function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setupAndCreateArticle();
        $this->fragment = $this->setupAndCreateQuote($this->owner);
        $this->setupAndCreateSnippet($this->owner);

        $this->fragmentManager = $this->manager($this->fragment);
        $this->fragmentRepo = app(FragmentRepository::class);
    }

    /** @test */
    public function it_can_select_new_fragments()
    {
        $response = $this->asAdmin()->get($this->manager($this->owner)->route('fragments-select-new', $this->owner));
        $response->assertSuccessful();

        $viewData = $response->getOriginalContent()->getData();

        $this->assertCount(2, $viewData['fragments']);
    }

    /** @test */
    public function it_can_select_existing_fragments()
    {
        // Create shareable fragment
        $owner2 = ArticlePage::create();
        $quote = Quote::create();
        $this->createAsFragment($quote, $owner2, 0);

        $response = $this->asAdmin()->get($this->manager($this->owner)->route('fragments-select-existing', $this->owner));
        $response->assertSuccessful();

        $viewData = $response->getOriginalContent()->getData();

        $this->assertCount(1, $viewData['sharedFragments']);
    }

    /** @test */
    public function it_can_show_refreshed_fragment()
    {
        $response = $this->asAdmin()->get($this->manager($this->owner)->route('fragments-show', $this->owner));
        $response->assertSuccessful();
    }
}
