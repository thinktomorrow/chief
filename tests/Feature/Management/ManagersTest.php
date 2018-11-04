<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management;

use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeTranslation;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFakeWithValidation;
use Thinktomorrow\Chief\Tests\TestCase;

class ManagersTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        ManagedModelFake::migrateUp();
        ManagedModelFakeTranslation::migrateUp();
    }

    /** @test */
    public function it_can_find_a_manager_by_key()
    {
        app(Register::class)->register('foo', ManagerFake::class, ManagedModelFake::class);

        /** @var Managers $managers */
        $managers = app(Managers::class);

        $this->assertInstanceOf(ManagerFake::class, $managers->findByKey('foo'));
    }

    /** @test */
    public function it_can_find_a_manager_by_id()
    {
        $this->app['config']->set('thinktomorrow.chief.collections', [
            'products' => ManagerFakeWithValidation::class,
        ]);

        app(Register::class)->register('foo', ManagerFake::class, ManagedModelFake::class);
        app(Register::class)->register('bar', ManagerFakeWithValidation::class, ManagedModelFake::class);

        ManagedModelFake::create(['id' => 1]);

        /** @var Managers $managers */
        $managers = app(Managers::class);

        $this->assertInstanceOf(ManagerFakeWithValidation::class, $managers->findByKey('bar', 1));

        $this->assertEquals(1, $this->getProtectedModelProperty($managers->findByKey('bar', 1))->id);
    }

    private function getProtectedModelProperty($instance)
    {
        $reflect = new \ReflectionClass($instance);
        $property = $reflect->getProperty('model');
        $property->setAccessible(true);

        return $property->getValue($instance);
    }
}
