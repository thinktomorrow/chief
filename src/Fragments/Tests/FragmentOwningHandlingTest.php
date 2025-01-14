<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Tests;

use Thinktomorrow\Chief\Fragments\Repositories\FragmentRepository;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

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

    public function test_it_can_select_new_fragments()
    {
        $response = $this->asAdmin()->get($this->manager($this->owner)->route('fragments-select-new', $this->owner));
        $response->assertSuccessful();

        $viewData = $response->getOriginalContent()->getData();

        $this->assertCount(1, $viewData['fragments']); // Grouped by category
        $this->assertCount(2, reset($viewData['fragments']));
    }

    public function test_it_can_select_existing_fragments()
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

    public function test_it_can_show_refreshed_fragment()
    {
        $response = $this->asAdmin()->get($this->manager($this->owner)->route('fragments-show', $this->owner));
        $response->assertSuccessful();
    }
}
