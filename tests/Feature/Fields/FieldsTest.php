<?php

namespace Thinktomorrow\Chief\Tests\Feature\Fields;

use Thinktomorrow\Chief\Fields\Fields;
use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Management\Register;
use Thinktomorrow\Chief\Fields\Types\InputField;
use Thinktomorrow\Chief\Management\Registration;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagerFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFake;
use Thinktomorrow\Chief\Tests\Feature\Management\Fakes\ManagedModelFakeFirst;

class FieldsTest extends TestCase
{
    /** @test */
    public function it_can_return_all_keys()
    {
        $fields = new Fields([
            InputField::make('input-one'),
            InputField::make('input-two'),
        ]);

        $this->assertEquals(['input-one','input-two'],$fields->keys());
    }

    /** @test */
    public function it_can_merge_two_fields_objects()
    {
        $fields = new Fields([
            InputField::make('input-one'),
            InputField::make('input-two'),
        ]);

        $fields2 = new Fields([
            InputField::make('input-three'),
            InputField::make('input-four'),
        ]);

        $mergedFields = $fields->merge($fields2);

        // Explicitly check for 'key' because this is also a reserved callable in php: key();
        $this->assertCount(4, $mergedFields->all());
        $this->assertEquals(['input-one','input-two','input-three','input-four'],$mergedFields->keys());
    }

    /** @test */
    public function similar_keys_are_overwritten_with_the_latter()
    {
        $fields = new Fields([
            InputField::make('input-one'),
            InputField::make('input-two'),
        ]);

        $fields2 = new Fields([
            InputField::make('input-one'),
        ]);

        $mergedFields = $fields->merge($fields2);

        // Explicitly check for 'key' because this is also a reserved callable in php: key();
        $this->assertCount(2, $mergedFields->all());
        $this->assertEquals(['input-one','input-two'],$mergedFields->keys());
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
