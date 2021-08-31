<?php

namespace Thinktomorrow\Chief\Tests\Unit\Fields;

use Thinktomorrow\Chief\ManagedModels\Fields\FieldGroup;
use Thinktomorrow\Chief\ManagedModels\Fields\Fields;
use Thinktomorrow\Chief\ManagedModels\Fields\FieldWindow;
use Thinktomorrow\Chief\ManagedModels\Fields\Types\InputField;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\TestCase;

class FieldWindowTest extends TestCase
{
    /** @test */
    public function it_can_define_a_field_window()
    {
        $fields = new Fields([
            FieldWindow::open(),
                FieldGroup::open(),
                    InputField::make('input-one'),
                    InputField::make('input-two'),
                FieldGroup::close(),
            FieldWindow::close(),
        ]);

        $this->assertCount(1, $fields->all());
        $this->assertCount(2, $fields->allFields());
        $this->assertCount(1, $fields->allWindows());
    }

    /** @test */
    public function it_can_define_a_field_window_with_dynamically_added_fieldgroups()
    {
        $fields = new Fields([
            FieldWindow::open(),
                InputField::make('input-one'),
                InputField::make('input-two'),
            FieldWindow::close(),
        ]);

        $this->assertCount(2, $fields->all());
        $this->assertCount(2, $fields->allFields());
        $this->assertCount(1, $fields->allWindows());
    }

    /** @test */
    public function it_can_group_fieldgroups_per_window()
    {
        $fields = new Fields([
            FieldWindow::open(),
            FieldGroup::open(),
            InputField::make('input-one'),
            InputField::make('input-two'),
            FieldGroup::close(),
            FieldGroup::open(),
            InputField::make('input-three'),
            InputField::make('input-four'),
            FieldGroup::close(),
            FieldWindow::close(),
        ]);

        $this->assertCount(2, $fields->all());
        $this->assertCount(4, $fields->allFields());
        $this->assertCount(1, $fields->allWindows());

        $this->assertCount(2, $fields->allWindows()->first()->getFields()->all());
        $this->assertCount(4, $fields->allWindows()->first()->getFields()->allFields());
    }

    /** @test */
    public function it_can_find_window_by_id()
    {

        $fields = new Fields([
            $foobarWindow = FieldWindow::open('foobar'),
            InputField::make('input-one'),
            FieldWindow::close(),
        ]);

        $this->assertEquals($foobarWindow->getId(), $fields->findWindow('foobar')->getId());
        $this->assertNull($fields->findWindow('xxx'));
    }

    /** @test */
    public function it_can_return_all_fields_that_dont_belong_to_a_fieldwindow()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function it_can_return_all_window_keys()
    {
        $this->markTestIncomplete();
    }

    /** @test */
    public function it_can_filter_fields_by_window_key()
    {
        $this->markTestIncomplete();
    }

    private function createFields(): Fields
    {
        return new Fields($this->values());
    }

    private function values(): array
    {
        return [
            FieldGroup::make([
                InputField::make('input-one'),
                InputField::make('input-two'),
            ]),
            FieldGroup::make([
                InputField::make('input-three'),
                InputField::make('input-four'),
            ]),
        ];
    }
}
