<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management;

use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Management\Registration;
use Thinktomorrow\Chief\Management\NonRegisteredManager;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeFirst;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeSecond;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFakeWithValidation;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeTranslation;

class RegisterTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        ManagedModelFakeFirst::migrateUp();
        ManagedModelFakeTranslation::migrateUp();
    }

    /** @test */
    public function it_can_register_a_manager()
    {
        $register = new Register();
        $register->register(ManagerFake::class, ManagedModelFakeFirst::class);

        $this->assertEquals('managed_model_first', $register->first()->key());
    }

    /** @test */
    public function it_can_register_multiple_managers()
    {
        $managerRegister = new Register();

        $managerRegister->register(ManagerFake::class, ManagedModelFakeFirst::class);
        $managerRegister->register(ManagerFake::class, ManagedModelFakeSecond::class);

        $this->assertCount(2, $managerRegister->all());
    }

    /** @test */
    public function it_cannot_register_an_incomplete_manager()
    {
        $this->expectException(\TypeError::class);

        (new Register())->register(null, ManagedModelFakeFirst::class);
    }

    /** @test */
    public function it_cannot_register_an_invalid_class()
    {
        $this->expectException(\InvalidArgumentException::class);

        (new Register())->register('bar', ManagedModelFake::class);
    }

    /** @test */
    public function it_cannot_register_a_class_that_isnt_a_manager()
    {
        $this->expectException(\InvalidArgumentException::class);

        (new Register())->register(ManagedModelFake::class, ManagedModelFake::class);
    }

    /** @test */
    public function it_cannot_register_an_invalid_model_class()
    {
        $this->expectException(\InvalidArgumentException::class);

        (new Register())->register(ManagerFake::class, 'bar');
    }

    /** @test */
    public function it_can_filter_by_class()
    {
        $register = new Register();
        $register->register(ManagerFake::class, ManagedModelFakeFirst::class);
        $register->register(ManagerFakeWithValidation::class, ManagedModelFakeSecond::class);

        $this->assertCount(1, $register->filterByClass(ManagerFake::class)->all());
        $this->assertEquals('managed_model_second', $register->filterByClass(ManagerFakeWithValidation::class)->first()->key());

        $this->assertCount(1, $register->rejectByClass(ManagerFake::class)->all());
        $this->assertEquals('managed_model_first', $register->rejectByClass(ManagerFakeWithValidation::class)->first()->key());
    }

    /** @test */
    public function it_can_filter_by_model()
    {
        $register = new Register();
        $register->register(ManagerFake::class, ManagedModelFakeFirst::class);
        $register->register(ManagerFakeWithValidation::class, ManagedModelFakeTranslation::class);

        $this->assertCount(1, $register->filterByModel(ManagedModelFakeFirst::class)->all());
        $this->assertEquals('managed_model_trans', $register->filterByModel(ManagedModelFakeTranslation::class)->first()->key());

        $this->assertCount(1, $register->rejectByModel(ManagedModelFakeFirst::class)->all());
        $this->assertEquals('managed_model_first', $register->rejectByModel(ManagedModelFakeTranslation::class)->first()->key());
    }

    /** @test */
    public function filtering_by_unknown_class_throws_exception()
    {
        $this->expectException(NonRegisteredManager::class);

        $register = new Register();
        $register->register(ManagerFake::class, ManagedModelFakeFirst::class);

        $register->filterByClass(ManagerFakeWithValidation::class)->all();
    }

    /** @test */
    public function it_overwrites_an_already_registered_manager()
    {
        $managerRegister = new Register();

        $managerRegister->register(ManagerFake::class, ManagedModelFakeFirst::class);
        $managerRegister->register(ManagerFakeWithValidation::class, ManagedModelFakeFirst::class);

        $this->assertCount(1, $managerRegister->all());
        $this->assertEquals([
            'managed_model_first' => new Registration(ManagerFakeWithValidation::class, ManagedModelFakeFirst::class)
        ], $managerRegister->all());
    }
}
