<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Controllers;

use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class CreateFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;

    protected function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
        $this->owner = $this->setupAndCreateArticle();
    }

    public function test_admin_can_view_a_root_fragment_create_form()
    {
        $context = FragmentTestHelpers::createContext($this->owner);

        $this->asAdmin()->get(route('chief::fragments.create', [$context->id, SnippetStub::resourceKey(), 1]))
            ->assertStatus(200)
            ->assertViewIs('chief-fragments::create');
    }

    public function test_admin_can_view_a_fragment_create_form()
    {
        $context = FragmentTestHelpers::createContext($this->owner);
        $fragment = FragmentTestHelpers::createFragment(SnippetStub::class);

        $this->asAdmin()->get(route('chief::fragments.nested.create', [$context->id, SnippetStub::resourceKey(), $fragment->getFragmentId(), 1]))
            ->assertStatus(200)
            ->assertViewIs('chief-fragments::create');
    }

    public function test_guests_cannot_view_the_create_form()
    {
        $context = FragmentTestHelpers::createContext($this->owner);

        $this->get(route('chief::fragments.create', [$context->id, SnippetStub::resourceKey(), 1]))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }
}
