<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management;

use Thinktomorrow\Chief\Management\NotAllowedManagerRoute;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModel;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelTranslation;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerWithoutDestroyFake;
use Thinktomorrow\Chief\Tests\TestCase;

class DeleteManagerTest extends TestCase
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
    public function it_can_delete_a_model()
    {
        $this->asAdmin()
            ->delete($this->fake->route('destroy'),[
                'deleteconfirmation' => 'DELETE',
            ]);

        $this->assertNull($this->model->fresh());
    }

    /** @test */
    public function deleting_a_model_requires_the_proper_confirmation_string()
    {
        $this->asAdmin()
            ->delete($this->fake->route('destroy'),[
                'deleteconfirmation' => 'FOOBAR',
            ]);

        $this->assertNotNull($this->model->fresh());
    }

    /** @test */
    public function it_should_not_delete_a_model_when_route_is_not_provided_by_manager()
    {
        $this->disableExceptionHandling();
        $this->expectException(NotAllowedManagerRoute::class);

        app(Register::class)->register('fakes', ManagerWithoutDestroyFake::class);
        app(ManagerWithoutDestroyFake::class)->manage($this->model);

        $this->asAdmin()
            ->delete('/admin/manage/fakes/1', [
                'deleteconfirmation' => 'DELETE',
            ]); // We force the url since it is not provided by the manager

        $this->assertNotNull($this->model->fresh());
    }
}
