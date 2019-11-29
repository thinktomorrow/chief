<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management;

use Thinktomorrow\Chief\Relations\Relation;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFake;
use Thinktomorrow\Chief\Management\Exceptions\NotAllowedManagerRoute;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeFirst;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFakeWithoutDelete;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeTranslation;

class DeleteManagerTest extends TestCase
{
    private $fake;
    private $model;

    protected function setUp(): void
    {
        parent::setUp();

        ManagedModelFakeFirst::migrateUp();
        ManagedModelFakeTranslation::migrateUp();

        $this->setUpDefaultAuthorization();

        app(Register::class)->register(ManagerFake::class, ManagedModelFakeFirst::class);

        $this->model = ManagedModelFakeFirst::create(['title' => 'Foobar', 'custom_column' => 'custom']);
        $this->fake = (new ManagerFake(app(Register::class)->filterByKey('managed_model_first')->first()))->manage($this->model);
    }

    /** @test */
    public function it_can_delete_a_model()
    {
        $this->asAdmin()
            ->delete($this->fake->route('delete'));

        $this->assertNull($this->model->fresh());
    }

    /** @test */
    public function it_should_not_delete_a_model_when_route_is_not_provided_by_manager()
    {
        $this->disableExceptionHandling();
        $this->expectException(NotAllowedManagerRoute::class);

        app(Register::class)->register(ManagerFakeWithoutDelete::class, ManagedModelFakeFirst::class);
        (new ManagerFakeWithoutDelete(app(Register::class)->first()))->manage($this->model);

        $this->asAdmin()
            ->delete('/admin/manage/managed_model_first/1'); // We force the url since it is not provided by the manager

        $this->assertNotNull($this->model->fresh());
    }

    /** @test */
    public function it_deletes_any_relation_entries_as_well()
    {
        $this->disableExceptionHandling();
        $childModel = ManagedModelFakeFirst::create(['title' => 'Child model']);
        $this->model->adoptChild($childModel);

        $this->assertEquals(1, Relation::count());

        $this->asAdmin()
            ->delete($this->fake->route('delete'));

        $this->assertNull($this->model->fresh());
        $this->assertEquals(0, Relation::count());

    }
}
