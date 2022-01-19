<?php

namespace Thinktomorrow\Chief\Tests\Application\Fragments\Crud;

use function chiefRegister;
use function route;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\FragmentFakes\SnippetStub;

class CreateFragmentTest extends ChiefTestCase
{
    /** @test */
    public function admin_can_view_the_fragment_create_form()
    {
        chiefRegister()->fragment(SnippetStub::class);

        $model = $this->setupAndCreateArticle();
        $manager = $this->manager(SnippetStub::managedModelKey());

        $this->asAdmin()->get($manager->route('fragment-create', $model))
            ->assertStatus(200)
            ->assertViewIs('chief::manager.windows.fragments.create');
    }

    /** @test */
    public function admin_can_view_create_form_for_a_nested_fragment()
    {
        $model = $this->setupAndCreateSnippet($this->setupAndCreateArticle());
        $manager = $this->manager($model);

        $this->asAdmin()->get($manager->route('fragment-create', $model))
            ->assertStatus(200)
            ->assertViewIs('chief::manager.windows.fragments.create');
    }

    /** @test */
    public function guests_cannot_view_the_create_form()
    {
        $model = $this->setupAndCreateSnippet($this->setupAndCreateArticle());
        $manager = $this->manager($model);

        $this->get($manager->route('fragment-create', $model))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }
}
