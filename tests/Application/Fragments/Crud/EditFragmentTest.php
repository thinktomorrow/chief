<?php

namespace Thinktomorrow\Chief\Tests\Application\Fragments\Crud;

use function auth;
use function route;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;

class EditFragmentTest extends ChiefTestCase
{
    private ArticlePage $owner;
    private Quote $model;
    private $fragmentManager;

    public function setUp(): void
    {
        parent::setUp();

        $this->owner = $this->setupAndCreateArticle();
        $this->model = $this->setupAndCreateQuote($this->owner);

        $this->fragmentManager = $this->manager($this->model);
    }

    /** @test */
    public function admin_can_view_the_fragment_edit_form()
    {
        $this->asAdmin()
            ->get($this->fragmentManager->route('fragment-edit', $this->owner, $this->model))
            ->assertStatus(200);
    }

    /** @test */
    public function admin_can_view_the_edit_form_of_a_nested_fragment()
    {
        $model = $this->setupAndCreateQuote($this->model, [], 0, false);

        $this->asAdmin()
            ->get($this->manager($model)->route('fragment-edit', $this->model, $model))
            ->assertStatus(200);
    }

    /** @test */
    public function guests_cannot_view_the_edit_form()
    {
        // Make sure that this admin is logged out
        auth()->guard('chief')->logout();

        $this->get($this->fragmentManager->route('fragment-edit', $this->owner, $this->model))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }
}
