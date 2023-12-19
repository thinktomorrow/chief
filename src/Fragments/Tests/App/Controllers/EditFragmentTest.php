<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Controllers;

use Thinktomorrow\Chief\Fragments\Tests\FragmentTestAssist;
use function auth;
use function route;
use Thinktomorrow\Chief\Fragments\App\Actions\AttachFragment;
use Thinktomorrow\Chief\Fragments\App\Actions\CreateFragment;
use Thinktomorrow\Chief\Fragments\Resource\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Resource\Models\ContextRepository;
use Thinktomorrow\Chief\Fragments\Resource\Models\FragmentModel;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class EditFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;
    private Quote $fragment;

    public function setUp(): void
    {
        parent::setUp();

        chiefRegister()->fragment(SnippetStub::class);
        $this->owner = $this->setupAndCreateArticle();
        $context = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'nl');
        $this->fragment = FragmentTestAssist::createAndAttachFragment(Quote::class, $context->id);
    }

    public function test_admin_can_view_the_fragment_edit_form()
    {
        $context = app(ContextRepository::class)->findOrCreateByOwner($this->owner, 'nl');

        $this->asAdmin()
            ->get(route('chief::fragments.edit', [$context->id, $this->fragment->fragmentModel()->id]))
            ->assertStatus(200);
    }

    public function test_admin_can_view_the_edit_form_of_a_nested_fragment()
    {
        $fragmentId = app(CreateFragment::class)->handle(SnippetStub::resourceKey(), ['title' => 'owning fragment'], []);
        $context = ContextModel::create(['owner_type' => FragmentModel::resourceKey(), 'owner_id' => $fragmentId, 'locale' => 'nl']);

        $this->asAdmin()
            ->get(route('chief::fragments.nested.edit', [$context->id, $this->fragment->fragmentModel()->id]))
            ->assertStatus(200);
    }

    public function test_admin_can_view_the_edit_form_of_a_shared_fragment()
    {
        $owner = ArticlePage::create();
        $owner2 = ArticlePage::create();
        $context = ContextModel::create(['owner_type' => $owner->getMorphClass(), 'owner_id' => $owner->id, 'locale' => 'nl']);
        $context2 = ContextModel::create(['owner_type' => $owner2->getMorphClass(), 'owner_id' => $owner2->id, 'locale' => 'nl']);

        $fragment = FragmentTestAssist::createAndAttachFragment(Quote::class, $context->id);
        app(AttachFragment::class)->handle($context2->id, $fragment->getFragmentId(), 1);

        $this->asAdmin()
            ->get(route('chief::fragments.edit', [$context->id, $fragment->getFragmentId()]))
            ->assertStatus(200);
    }

    public function test_guests_cannot_view_the_edit_form()
    {
        // Make sure that this admin is logged out
        auth()->guard('chief')->logout();

        $this->get(route('chief::fragments.edit', [ContextModel::first()->id, $this->fragment->getFragmentId()]))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }
}
