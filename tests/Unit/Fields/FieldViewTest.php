<?php

namespace Thinktomorrow\Chief\Tests\Unit\Fields;

use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Fields\Types\InputField;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeFirst;

class FieldViewTest extends TestCase
{
    /** @test */
    public function it_can_have_a_custom_view()
    {
        $this->app['view']->addNamespace('test-views', __DIR__ . '/stubs/views');

        app(Register::class)->register(ManagerFake::class, ManagedModelFakeFirst::class);
        $manager = (new ManagerFake(app(Register::class)->filterByKey('managed_model_first')->first()));

        $render = $manager->renderField(
            InputField::make('input-one')->view('test-views::custom-field')
        );

        $this->assertEquals('this is a custom field view',$render);
    }

    /** @test */
    public function it_can_have_a_custom_element_view()
    {
        $this->app['view']->addNamespace('test-views', __DIR__ . '/stubs/views');

        app(Register::class)->register(ManagerFake::class, ManagedModelFakeFirst::class);
        $manager = (new ManagerFake(app(Register::class)->filterByKey('managed_model_first')->first()));

        $render = $manager->renderField(
            InputField::make('input-one')->elementView('test-views::custom-element')
        );

        $this->assertStringContainsString('this is a custom element view',$render);
    }
}
