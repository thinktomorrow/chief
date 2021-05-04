<?php

namespace Thinktomorrow\Chief\Tests\Application\Fragments;

use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Managers\Presets\FragmentManager;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Tests\ChiefTestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Shared\Fakes\Quote;
use Thinktomorrow\Chief\Tests\Shared\ManagerFactory;

class EditFragmentTest extends ChiefTestCase
{
    private $owner;
    private $fragmentManager;

    public function setUp(): void
    {
        parent::setUp();

        ArticlePage::migrateUp();
        Quote::migrateUp();

        ManagerFactory::make()->withModel(ArticlePage::class)->create();
        $this->owner = ArticlePage::create();

        chiefRegister()->model(Quote::class, FragmentManager::class);
        $this->fragmentManager = app(Registry::class)->manager(Quote::managedModelKey());

        // Store the fragment first
        $this->asAdmin()->post($this->fragmentManager->route('fragment-store', $this->owner), [
            'title' => 'existing-title',
            'custom' => 'existing-custom-value',
        ]);
    }

    /** @test */
    public function admin_can_view_the_fragment_edit_form()
    {
        $this->disableExceptionHandling();
        $model = app(FragmentRepository::class)->getByOwner($this->owner)->first();

        $this->asAdmin()->get($this->fragmentManager->route('fragment-edit', $this->owner, $model))
            ->assertStatus(200)
            ->assertViewIs('chief::manager.cards.fragments.edit');
    }

    /** @test */
    public function guests_cannot_view_the_edit_form()
    {
        // Make sure that this admin is logged out
        auth()->guard('chief')->logout();

        $model = app(FragmentRepository::class)->getByOwner($this->owner)->first();

        $this->get($this->fragmentManager->route('fragment-edit', $this->owner, $model))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }
}
