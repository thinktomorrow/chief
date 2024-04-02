<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Controllers;

use Thinktomorrow\Chief\Fragments\Tests\FragmentTestAssist;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class CreateFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;

    public function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
        $this->owner = $this->setupAndCreateArticle();
    }

    public function test_admin_can_view_the_fragment_create_form()
    {
        $context = FragmentTestAssist::createContext($this->owner);

        $this->asAdmin()->get(route('chief::fragments.create', [$context->id, SnippetStub::resourceKey(), 1]))
            ->assertStatus(200)
            ->assertViewIs('chief-fragments::create');
    }

    public function test_admin_can_view_create_form_for_a_nested_fragment()
    {
        $fragment = FragmentTestAssist::createFragment(SnippetStub::class);
        $context = FragmentTestAssist::createContext($fragment);

        $this->asAdmin()->get(route('chief::fragments.nested.create', [$context->id, SnippetStub::resourceKey(), 1]))
            ->assertStatus(200)
            ->assertViewIs('chief-fragments::create');
    }

    public function test_guests_cannot_view_the_create_form()
    {
        $context = FragmentTestAssist::createContext($this->owner);

        $this->get(route('chief::fragments.create', [$context->id, SnippetStub::resourceKey(), 1]))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }
}
