<?php

namespace Thinktomorrow\Chief\Tests\Unit\FormGroups;

use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Fields\Types\InputField;
use Thinktomorrow\Chief\Fields\FormGroups\FormGroup;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeFirst;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFake;
use Thinktomorrow\Chief\Tests\TestCase;

class FormGroupTest extends TestCase
{
    /** @test */
    public function it_can_return_label_and_description_and_fields()
    {
        $formgroup = FormGroup::make(new Fields([
            InputField::make('input-two'),
            InputField::make('input-three'),
        ]));

        $formgroup->label('fragment-label')
                  ->description('fragment-description');

        $this->assertInstanceOf(Fields::class, $formgroup->fields());
        $this->assertEquals('fragment-label', $formgroup->getLabel());
        $this->assertEquals('fragment-description', $formgroup->getDescription());
    }

    /** @test */
    public function a_single_field_has_a_formgroup_specific_view_by_default()
    {
        $formgroup = FormGroup::make(new Fields([
            InputField::make('input-two'),
        ]));

        $this->assertEquals('chief::back._formgroups.fieldgroup', $formgroup->fields()->first()->getView());
    }

    /** @test */
    public function a_custom_single_field_view_stays_unaltered()
    {
        $formgroup = FormGroup::make(new Fields([
            InputField::make('input-two')->view('custom_view'),
        ]));

        $this->assertEquals('custom_view', $formgroup->fields()->first()->getView());
    }

    /** @test */
    public function it_can_be_rendered_for_edit()
    {
        $this->app['view']->addNamespace('test-views', __DIR__ . '/../Fields/stubs/views');

        app(Register::class)->register(ManagerFake::class, ManagedModelFakeFirst::class);
        $manager = (new ManagerFake(app(Register::class)->filterByKey('managed_model_first')->first()));

        $render = $manager->renderFormGroup(
            FormGroup::make(new Fields([
                InputField::make('input-one')->view('test-views::custom-field'),
            ]))
        );

        $this->assertStringContainsString('this is a custom field view',$render);
    }
}
