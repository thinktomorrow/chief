<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Controllers;

use Thinktomorrow\Chief\Fragments\Tests\FragmentTestAssist;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class SelectFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setupAndCreateArticle();
    }

    public function test_admin_can_view_the_fragment_select_new()
    {
        $context = FragmentTestAssist::findOrCreateContext($this->owner);

        $this->asAdmin()
            ->get(route('chief::fragments.new', [$context->id]))
            ->assertStatus(200);
    }

    public function test_admin_can_view_the_fragment_select_existing()
    {
        FragmentTestAssist::createContextAndAttachFragment($this->owner, Quote::class);
        $context2 = FragmentTestAssist::createContext(ArticlePage::create());

        $this->asAdmin()
            ->get(route('chief::fragments.existing', [$context2->id]))
            ->assertViewHas('sharedFragments', fn ($sharedFragments) => count($sharedFragments) == 1)
            ->assertStatus(200);
    }

    public function test_admin_can_view_the_fragment_select_existing_without_any_fragments_available()
    {
        $context = FragmentTestAssist::findOrCreateContext($this->owner);

        $this->asAdmin()
            ->get(route('chief::fragments.existing', [$context->id]))
            ->assertViewHas('sharedFragments', fn ($sharedFragments) => count($sharedFragments) == 0)
            ->assertStatus(200);
    }

    public function test_admin_can_view_the_fragment_select_existing_and_ignores_already_selected_ones()
    {
        [$context] = FragmentTestAssist::createContextAndAttachFragment($this->owner, Quote::class);

        $this->asAdmin()
            ->get(route('chief::fragments.existing', [$context->id]))
            ->assertViewHas('sharedFragments', fn ($sharedFragments) => count($sharedFragments) == 0)
            ->assertStatus(200);
    }
}
