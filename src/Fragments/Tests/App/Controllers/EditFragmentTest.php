<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Controllers;

use function auth;
use function route;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Tests\FragmentTestHelpers;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;

use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class EditFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;

    private Quote $fragment;

    private ContextModel $context;

    protected function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
        $this->owner = $this->setupAndCreateArticle();
        $this->context = FragmentTestHelpers::createContext($this->owner);
        $this->fragment = FragmentTestHelpers::createAndAttachFragment(Quote::class, $this->context->id);
    }

    public function test_admin_can_view_the_fragment_edit_form()
    {
        $this->disableExceptionHandling();
        $this->asAdmin()
            ->get(route('chief::fragments.edit', [$this->context->id, $this->fragment->getFragmentModel()->id]))
            ->assertStatus(200);
    }

    public function test_admin_can_view_the_edit_form_of_a_nested_fragment()
    {
        $this->asAdmin()
            ->get(route('chief::fragments.nested.edit', [$this->context->id, $this->fragment->getFragmentModel()->id]))
            ->assertStatus(200);
    }

    public function test_admin_can_view_the_edit_form_of_a_shared_fragment()
    {
        $owner2 = ArticlePage::create();
        $context2 = FragmentTestHelpers::createContext($owner2);
        FragmentTestHelpers::attachFragment($context2->id, $this->fragment->getFragmentId());

        // Assert it is shared
        $this->assertTrue(FragmentTestHelpers::findFragment($this->fragment->getFragmentId())->getFragmentModel()->isShared());

        $this->asAdmin()
            ->get(route('chief::fragments.edit', [$this->context->id, $this->fragment->getFragmentId()]))
            ->assertStatus(200);
    }

    public function test_guests_cannot_view_the_edit_form()
    {
        // Make sure that this admin is logged out
        auth()->guard('chief')->logout();

        $this->get(route('chief::fragments.edit', [$this->context->id, $this->fragment->getFragmentId()]))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }
}
