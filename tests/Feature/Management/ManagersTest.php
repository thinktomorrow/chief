<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management;

use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Management\Exceptions\NonExistingRecord;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeFirst;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeSecond;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFakeWithValidation;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeTranslation;

class ManagersTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ManagedModelFakeFirst::migrateUp();
        ManagedModelFakeTranslation::migrateUp();
    }

    /** @test */
    public function it_can_find_a_manager_by_key()
    {
        app(Register::class)->register(ManagerFake::class, ManagedModelFakeFirst::class);

        /** @var Managers $managers */
        $managers = app(Managers::class);

        $this->assertInstanceOf(ManagerFake::class, $managers->findByKey('managed_model_first'));
    }

    /** @test */
    public function it_can_find_a_manager_by_id()
    {
        $this->app['config']->set('thinktomorrow.chief.collections', [
            'products' => ManagerFakeWithValidation::class,
        ]);

        app(Register::class)->register(ManagerFake::class, ManagedModelFakeFirst::class);
        app(Register::class)->register(ManagerFakeWithValidation::class, ManagedModelFakeSecond::class);

        ManagedModelFakeFirst::create(['id' => 1]);

        /** @var Managers $managers */
        $managers = app(Managers::class);

        $this->assertInstanceOf(ManagerFakeWithValidation::class, $managers->findByKey('managed_model_second', 1));

        $this->assertEquals(1, $this->getProtectedModelProperty($managers->findByKey('managed_model_second', 1))->id);
    }

    /** @test */
    public function it_throws_error_if_model_not_persisted_and_we_expect_it_to_be()
    {
        $this->disableExceptionHandling();
        $this->expectException(NonExistingRecord::class);

        app(Register::class)->register(ManagerFake::class, ManagedModelFakeFirst::class);
        $this->fake = new ManagerFake(app(Register::class)->first());

        $response = $this->fake->route('update');
    }

    private function getProtectedModelProperty($instance)
    {
        $reflect = new \ReflectionClass($instance);
        $property = $reflect->getProperty('model');
        $property->setAccessible(true);

        return $property->getValue($instance);
    }
}
