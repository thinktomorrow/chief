<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management;

use Thinktomorrow\Chief\Management\NonRegisteredManager;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Management\Registration;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeTranslation;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFakeWithValidation;
use Thinktomorrow\Chief\Tests\TestCase;

class RegisterTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        ManagedModelFake::migrateUp();
        ManagedModelFakeTranslation::migrateUp();
    }

    /** @test */
    public function it_can_register_a_manager()
    {
        $register = new Register();
        $register->register('foo', ManagerFake::class, ManagedModelFake::class);

        $this->assertEquals('foo', $register->first()->key());
    }

    /** @test */
    public function it_can_register_multiple_managers()
    {
        $managerRegister = new Register();

        $managerRegister->register('one', ManagerFake::class, ManagedModelFake::class);
        $managerRegister->register('two', ManagerFake::class, ManagedModelFake::class);
        $managerRegister->register('three', ManagerFake::class, ManagedModelFake::class);
        $managerRegister->register('four', ManagerFake::class, ManagedModelFake::class);

        $this->assertCount(4, $managerRegister->all());
    }

    /** @test */
    public function it_cannot_register_an_incomplete_manager()
    {
        $this->expectException(\TypeError::class);

        (new Register())->register('foo', null, ManagedModelFake::class);
    }

    /** @test */
    public function it_cannot_register_an_invalid_class()
    {
        $this->expectException(\InvalidArgumentException::class);

        (new Register())->register('foo', 'bar', ManagedModelFake::class);
    }

    /** @test */
    public function it_cannot_register_a_class_that_isnt_a_manager()
    {
        $this->expectException(\InvalidArgumentException::class);

        (new Register())->register('foo', ManagedModelFake::class, ManagedModelFake::class);
    }

    /** @test */
    public function it_cannot_register_an_invalid_model_class()
    {
        $this->expectException(\InvalidArgumentException::class);

        (new Register())->register('foo', ManagerFake::class, 'bar');
    }

    /** @test */
    public function it_can_filter_by_class()
    {
        $register = new Register();
        $register->register('one', ManagerFake::class, ManagedModelFake::class);
        $register->register('two', ManagerFakeWithValidation::class, ManagedModelFake::class);

        $this->assertCount(1, $register->filterByClass(ManagerFake::class)->all());
        $this->assertEquals('two', $register->filterByClass(ManagerFakeWithValidation::class)->first()->key());

        $this->assertCount(1, $register->rejectByClass(ManagerFake::class)->all());
        $this->assertEquals('one', $register->rejectByClass(ManagerFakeWithValidation::class)->first()->key());
    }

    /** @test */
    public function it_can_filter_by_model()
    {
        $register = new Register();
        $register->register('one', ManagerFake::class, ManagedModelFake::class);
        $register->register('two', ManagerFakeWithValidation::class, ManagedModelFakeTranslation::class);

        $this->assertCount(1, $register->filterByModel(ManagedModelFake::class)->all());
        $this->assertEquals('two', $register->filterByModel(ManagedModelFakeTranslation::class)->first()->key());

        $this->assertCount(1, $register->rejectByModel(ManagedModelFake::class)->all());
        $this->assertEquals('one', $register->rejectByModel(ManagedModelFakeTranslation::class)->first()->key());
    }

    /** @test */
    public function filtering_by_unknown_class_throws_exception()
    {
        $this->expectException(NonRegisteredManager::class);

        $register = new Register();
        $register->register('one', ManagerFake::class, ManagedModelFake::class);

        $register->filterByClass(ManagerFakeWithValidation::class)->all();
    }

    /** @test */
    public function it_overwrites_an_already_registered_manager()
    {
        $managerRegister = new Register();

        $managerRegister->register('one', ManagerFake::class, ManagedModelFake::class);
        $managerRegister->register('one', ManagerFakeWithValidation::class, ManagedModelFake::class);

        $this->assertCount(1, $managerRegister->all());
        $this->assertEquals([
            'one' => new Registration('one', ManagerFakeWithValidation::class, ManagedModelFake::class)
        ], $managerRegister->all());
    }
}
