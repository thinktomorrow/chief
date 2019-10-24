<?php

namespace Thinktomorrow\Chief\Tests\Feature\Fields;

use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Fields\Types\InputField;
use Thinktomorrow\Chief\Tests\Feature\Audit\ArticleFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeFirst;

class FieldTest extends TestCase
{
    /** @test */
    function it_can_get_the_existing_value()
    {
        $model = ArticleFake::create(['title:en' => 'existing title']);
        $field = InputField::make('title')->translatable(['nl','en']);

        $this->assertEquals('existing title', $field->getFieldValue($model, 'en'));
        $this->assertNull($field->getFieldValue($model, 'nl'));
    }

    /** @test */
    function it_always_get_the_existing_value_without_locale_fallback()
    {
        $model = ArticleFake::create(['title:nl' => 'existing title']);
        $field = InputField::make('title')->translatable(['nl','en']);

        $this->assertEquals('existing title', $field->getFieldValue($model, 'nl'));
        $this->assertNull($field->getFieldValue($model, 'en'));
    }

    /** @test */
    function it_can_resolve_value_with_custom_function()
    {
        $field = InputField::make('title')->valueResolver(function(){
            return 'custom value';
        });

        $this->assertEquals('custom value', $field->getFieldValue(ArticleFake::create()));
    }

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
}
