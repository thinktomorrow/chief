<?php

namespace Thinktomorrow\Chief\Fragments\Tests\App\Controllers;

use Thinktomorrow\Chief\Fragments\App\Actions\CreateFragment;
use Thinktomorrow\Chief\Fragments\Resource\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Resource\Models\FragmentModel;
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
        $this->disableExceptionHandling();
        $context = ContextModel::create(['owner_type' => $this->owner->getMorphClass(), 'owner_id' => $this->owner->id, 'locale' => 'nl']);

        $this->asAdmin()->get(route('chief::fragments.create', [$context->id, SnippetStub::resourceKey(), 1]))
            ->assertStatus(200)
            ->assertViewIs('chief-fragments::create');
    }

    public function test_admin_can_view_create_form_for_a_nested_fragment()
    {
        $fragmentId = app(CreateFragment::class)->handle(SnippetStub::resourceKey(), ['title' => 'owning fragment'], []);
        $context = ContextModel::create(['owner_type' => FragmentModel::resourceKey(), 'owner_id' => $fragmentId, 'locale' => 'nl']);

        $this->asAdmin()->get(route('chief::fragments.nested.create', [$context->id, SnippetStub::resourceKey(), 1]))
            ->assertStatus(200)
            ->assertViewIs('chief-fragments::create');
    }

    public function test_guests_cannot_view_the_create_form()
    {
        $context = ContextModel::create(['owner_type' => $this->owner->getMorphClass(), 'owner_id' => $this->owner->id, 'locale' => 'nl']);

        $this->get(route('chief::fragments.create', [$context->id, SnippetStub::resourceKey(), 1]))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }
}
