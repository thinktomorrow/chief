<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management;

use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModel;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelTranslation;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFake;
use Thinktomorrow\Chief\Tests\TestCase;

class EditManagerTest extends TestCase
{
    private $fake;
    private $model;

    protected function setUp()
    {
        parent::setUp();

        ManagedModel::migrateUp();
        ManagedModelTranslation::migrateUp();

        $this->setUpDefaultAuthorization();

        app(Register::class)->register('fakes', ManagerFake::class);

        $this->model = ManagedModel::create(['title' => 'Foobar', 'custom_column' => 'custom']);
        $this->fake = app(ManagerFake::class)->manage($this->model);
    }

    /** @test */
    public function admin_can_view_the_edit_form()
    {
        $this->asAdmin()->get($this->fake->route('edit'))
            ->assertStatus(200);
    }

    /** @test */
    public function guests_cannot_view_the_edit_form()
    {
        $this->get($this->fake->route('edit'))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }
}
