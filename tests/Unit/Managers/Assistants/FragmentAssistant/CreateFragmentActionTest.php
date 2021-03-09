<?php

namespace Thinktomorrow\Chief\Tests\Unit\Managers\Assistants\FragmentAssistant;

use Thinktomorrow\Chief\Fragments\Assistants\FragmentAssistant;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\ManagerFactory;

class CreateFragmentActionTest extends ChiefTestCase
{
    /** @test */
    public function admin_can_view_the_fragment_create_form()
    {
        $this->disableExceptionHandling();

        $model = $this->setupAndCreateArticle();
        $manager = $this->manager($model);

        $this->asAdmin()->get($manager->route('fragment-create', $model))
            ->assertStatus(200)
            ->assertViewIs('chief::manager.fragments.create');
    }

    /** @test */
    public function guests_cannot_view_the_create_form()
    {
        ArticlePage::migrateUp();

        $manager = ManagerFactory::make()->withModel(ArticlePage::class)->withAssistants([FragmentAssistant::class])->create();
        $model = ArticlePage::create();

        $this->get($manager->route('fragment-create', $model))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }
}
