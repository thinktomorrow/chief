<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management;

use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeFirst;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeTranslation;

class CreateManagerTest extends TestCase
{
    private $fake;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpChiefEnvironment();

        ManagedModelFakeFirst::migrateUp();
        ManagedModelFakeTranslation::migrateUp();

        app(Register::class)->register(ManagerFake::class, ManagedModelFakeFirst::class);

        $this->fake = new ManagerFake(app(Register::class)->first());
    }

    /** @test */
    public function admin_can_view_the_create_form()
    {
        $this->asAdmin()->get($this->fake->route('create'))
            ->assertViewIs('chief::back.managers.create')
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
