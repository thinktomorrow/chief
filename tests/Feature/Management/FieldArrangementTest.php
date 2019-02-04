<?php

namespace Thinktomorrow\Chief\Tests\Feature\Management;

use Thinktomorrow\Chief\Fields\Types\Field;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeTranslation;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFakeWithFieldTabs;
use Thinktomorrow\Chief\Tests\TestCase;

class FieldArrangementTest extends TestCase
{
    private $manager;
    private $model;

    protected function setUp()
    {
        parent::setUp();

        ManagedModelFake::migrateUp();
        ManagedModelFakeTranslation::migrateUp();

        $this->setUpDefaultAuthorization();

        app(Register::class)->register('fakes', ManagerFake::class, ManagedModelFake::class);

        $this->model = ManagedModelFake::create(['title' => 'Foobar', 'custom_column' => 'custom']);
        $this->manager = (new ManagerFake(app(Register::class)->first()))->manage($this->model);
    }

    /** @test */
    public function by_default_fields_are_arranged_by_their_order_of_appearance()
    {
        $arrangement = $this->manager->fieldArrangement();

        $this->assertFalse($arrangement->hasTabs());
        $this->assertCount(6, $arrangement->fields());
        $this->assertEquals('title', $arrangement->fields()[0]->key);
        $this->assertEquals('avatar', $arrangement->fields()[4]->key);
        $this->assertEquals('doc', $arrangement->fields()[5]->key);
    }

    /** @test */
    public function fields_can_be_arranged_by_tabs()
    {
        app(Register::class)->register('fakes', ManagerFakeWithFieldTabs::class, ManagedModelFake::class);
        $manager = (new ManagerFakeWithFieldTabs(app(Register::class)->first()))->manage($this->model);
        $arrangement = $manager->fieldArrangement();

        $this->assertTrue($arrangement->hasTabs());

        $this->assertCount(3, $arrangement->tabs()[0]->fields());
        $this->assertCount(2, $arrangement->tabs()[1]->fields());
        $this->assertInstanceOf(Field::class, $arrangement->tabs()[1]->fields()[0]);
    }
}
