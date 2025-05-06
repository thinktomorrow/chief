<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\Views;

use Thinktomorrow\Chief\Forms\Fields\Date;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\Hidden;
use Thinktomorrow\Chief\Forms\Fields\Number;
use Thinktomorrow\Chief\Forms\Fields\Slider;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Fields\Textarea;
use Thinktomorrow\Chief\Forms\Fields\Time;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class RenderTextualFieldsTest extends ChiefTestCase
{
    private array $classes;

    protected function setUp(): void
    {
        parent::setUp();

        $this->classes = [
            Textarea::class => 'given value',
            Text::class => 'given value',
            Number::class => '2',
            Slider::class => 5,
            Date::class => '2022-02-02',
            Time::class => '9:23',
            Hidden::class => 'given value',
        ];
    }

    public function test_it_can_render_all_fields()
    {
        /** @var Field $class */
        foreach ($this->classes as $class => $value) {
            $component = $class::make('xxx')->value($value);
            $this->assertStringContainsString('name="xxx"', $component->toHtml());
            $this->assertStringContainsString($value, $component->toHtml());
        }
    }

    public function test_it_can_render_localized_fields()
    {
        /** @var Field $class */
        foreach (array_keys($this->classes) as $class) {
            $component = $class::make('xxx')->locales(['nl', 'en'])->value([
                'nl' => $valueNL = 'value-nl',
                'en' => $valueEN = 'value-en',
            ]);

            $render = $component->toHtml();

            $this->assertStringContainsString('xxx[nl]', $render);
            $this->assertStringContainsString('xxx[en]', $render);
            $this->assertStringContainsString($valueNL, $render);
            $this->assertStringContainsString($valueEN, $render);
        }
    }

    public function test_it_can_render_all_fields_in_a_window()
    {
        /** @var Field $class */
        foreach ($this->classes as $class => $value) {
            $component = $class::make('xxx')->value($value);

            if ($component instanceof Hidden) {
                $this->assertStringNotContainsString($value, $component->renderPreview());
            } elseif ($component instanceof Date) {
                $this->assertStringContainsString('02/02/2022', $component->renderPreview());
            } else {
                $this->assertStringContainsString($value, $component->renderPreview());
            }
        }
    }

    public function test_it_can_render_a_custom_view()
    {
        $this->app['view']->addNamespace('test-views', __DIR__.'/../../TestSupport/stubs/views');

        $this->assertStringContainsString(
            'this is a custom field view',
            Text::make('xxx')->setView('test-views::custom-field')->render()
        );
    }

    public function test_number_field_accepts_floats()
    {
        $numberField = Number::make('number')->step(0.2)->value(2.5);

        $this->assertEquals(2.5, $numberField->getValue());
        $this->assertEquals(0.2, $numberField->getStep());
    }
}
