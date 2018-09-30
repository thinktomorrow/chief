<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management;

use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Tests\Fakes\ArticlePageFake;
use Thinktomorrow\Chief\Tests\Fakes\ProductPageFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeTranslation;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerWithValidationFake;
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
    function it_can_register_a_manager()
    {
        $register = new Register();
        $register->register('foo',ManagerFake::class);

        $this->assertEquals('foo', $register->toKey());
    }

    /** @test */
    function it_can_register_multiple_managers()
    {
        $managerRegister = new Register();

        $managerRegister->register('one', ManagerFake::class);
        $managerRegister->register('two', ManagerFake::class);
        $managerRegister->register('three', ManagerFake::class);
        $managerRegister->register('four', ManagerFake::class);

        $this->assertCount(4, $managerRegister->all());
    }

    /** @test */
    function it_cannot_register_an_incomplete_manager()
    {
        $this->expectException(\InvalidArgumentException::class);

        (new Register())->register('foo', null);
    }

    /** @test */
    function it_cannot_register_an_invalid_class()
    {
        $this->expectException(\InvalidArgumentException::class);

        (new Register())->register('foo','bar');
    }

    /** @test */
    function it_can_list_all_keys()
    {
        $register = new Register();
        $register->register('one',ManagerFake::class);
        $register->register('two',ManagerFake::class);

        $this->assertEquals(['one', 'two'], $register->toKeys());
    }

    /** @test */
    function it_can_filter_by_class()
    {
        $register = new Register();
        $register->register('one',ManagerFake::class);
        $register->register('two',ManagerWithValidationFake::class);

        $this->assertCount(1, $register->filterByClass(ManagerFake::class)->all());
        $this->assertEquals('two', $register->filterByClass(ManagerWithValidationFake::class)->toKey());

        $this->assertCount(1, $register->rejectByClass(ManagerFake::class)->all());
        $this->assertEquals('one', $register->rejectByClass(ManagerWithValidationFake::class)->toKey());
    }

    /** @test */
    function it_overwrites_an_already_registered_manager()
    {
        $managerRegister = new Register();

        $managerRegister->register('one', ManagerFake::class);
        $managerRegister->register('one', ManagerWithValidationFake::class);

        $this->assertCount(1, $managerRegister->all());
        $this->assertEquals(['one' => [
            'key' => 'one',
            'class' => ManagerWithValidationFake::class,
        ]
        ], $managerRegister->all());
    }

}
