<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management;

use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeTranslation;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFake;
use Thinktomorrow\Chief\Tests\TestCase;

class CreateManagerTest extends TestCase
{
    private $fake;

    protected function setUp()
    {
        parent::setUp();

        ManagedModelFake::migrateUp();
        ManagedModelFakeTranslation::migrateUp();

        $this->setUpDefaultAuthorization();

        app(Register::class)->register('fakes', ManagerFake::class);

        $this->fake = app(ManagerFake::class);
    }

    /** @test */
    public function admin_can_view_the_create_form()
    {
        $this->disableExceptionHandling();
        $this->asAdmin()->get($this->fake->route('create'))
            ->assertStatus(200);
    }

    /** @test */
    public function guests_cannot_view_the_create_form()
    {
        $this->get($this->fake->route('create'))
            ->assertStatus(302)
            ->assertRedirect(route('chief.back.login'));
    }
}
