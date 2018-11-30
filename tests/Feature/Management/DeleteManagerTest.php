<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management;

use Thinktomorrow\Chief\Management\NotAllowedManagerRoute;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeTranslation;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFakeWithoutDelete;
use Thinktomorrow\Chief\Tests\TestCase;

class DeleteManagerTest extends TestCase
{
    private $fake;
    private $model;

    protected function setUp()
    {
        parent::setUp();

        ManagedModelFake::migrateUp();
        ManagedModelFakeTranslation::migrateUp();

        $this->setUpDefaultAuthorization();

        app(Register::class)->register('fakes', ManagerFake::class, ManagedModelFake::class);

        $this->model = ManagedModelFake::create(['title' => 'Foobar', 'custom_column' => 'custom']);
        $this->fake = (new ManagerFake(app(Register::class)->filterByKey('fakes')->first()))->manage($this->model);
    }

    /** @test */
    public function it_can_delete_a_model()
    {
        $this->disableExceptionHandling();

        $this->asAdmin()
            ->delete($this->fake->route('delete'));

        $this->assertNull($this->model->fresh());
    }

    /** @test */
    public function it_should_not_delete_a_model_when_route_is_not_provided_by_manager()
    {
        $this->disableExceptionHandling();
        $this->expectException(NotAllowedManagerRoute::class);

        app(Register::class)->register('fakes', ManagerFakeWithoutDelete::class, ManagedModelFake::class);
        (new ManagerFakeWithoutDelete(app(Register::class)->first()))->manage($this->model);

        $this->asAdmin()
            ->delete('/admin/manage/fakes/1'); // We force the url since it is not provided by the manager

        $this->assertNotNull($this->model->fresh());
    }
}
