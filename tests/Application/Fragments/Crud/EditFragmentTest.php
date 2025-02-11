<?php

namespace Thinktomorrow\Chief\Tests\Application\Fragments\Crud;

use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

use function auth;
use function route;

class EditFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;

    private Quote $model;

    private $fragmentManager;

    protected function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setupAndCreateArticle();
        $this->model = $this->setupAndCreateQuote($this->owner);

        $this->fragmentManager = $this->manager($this->model);
    }

    public function test_admin_can_view_the_fragment_edit_form()
    {
        $this->asAdmin()
            ->get($this->fragmentManager->route('fragment-edit', $this->owner, $this->model))
            ->assertStatus(200);
    }

    public function test_admin_can_view_the_edit_form_of_a_nested_fragment()
    {
        $model = $this->setupAndCreateQuote($this->model, [], 0, false);

        $this->asAdmin()
            ->get($this->manager($model)->route('fragment-edit', $this->model, $model))
            ->assertStatus(200);
    }

    public function test_admin_can_view_the_edit_form_of_a_shared_fragment()
    {
        $model = $this->setupAndCreateQuote($this->model, [], 0, false);

        // Make fragment shareable
        $this->addFragment($model, $this->owner);

        $this->asAdmin()
            ->get($this->manager($model)->route('fragment-edit', $this->model, $model))
            ->assertStatus(200);
    }

    public function test_guests_cannot_view_the_edit_form()
    {
        // Make sure that this admin is logged out
        auth()->guard('chief')->logout();

        $this->get($this->fragmentManager->route('fragment-edit', $this->owner, $this->model))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }
}
