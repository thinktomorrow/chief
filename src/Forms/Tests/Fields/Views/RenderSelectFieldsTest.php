<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\Views;

use Thinktomorrow\Chief\Forms\Fields\Checkbox;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\MultiSelect;
use Thinktomorrow\Chief\Forms\Fields\Radio;
use Thinktomorrow\Chief\Forms\Fields\Select;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class RenderSelectFieldsTest extends ChiefTestCase
{
    private array $classes;

    protected function setUp(): void
    {
        parent::setUp();

        $this->classes = [
            Select::class => 'one',
            MultiSelect::class => 'two',
            Radio::class => 'one',
            Checkbox::class => 'two',
        ];
    }

    public function test_it_can_render_all_fields()
    {
        /** @var Field $class */
        foreach ($this->classes as $class => $value) {
            $component = $class::make('xxx')->options(['one' => 'one-value', 'two' => 'two-value'])->value($value);
            $this->assertStringContainsString('name="xxx', $component->toHtml());
            $this->assertStringContainsString($value, $component->toHtml());
        }
    }

    public function test_it_can_render_select_field_with_nested_options()
    {
        $component = Select::make('xxx')->options([
            ['value' => 2, 'label' => 'first product'],
            ['value' => 5, 'label' => 'second product'],
            ['value' => 1, 'label' => 'third product'],
        ]);
        $this->assertStringContainsString('name="xxx', $component->toHtml());
    }

    public function test_it_can_render_localized_fields()
    {
        /** @var Field $class */
        foreach (array_keys($this->classes) as $class) {
            $component = $class::make('xxx')->options(['one' => 'one-value', 'two' => 'two-value'])->locales(['nl', 'en'])->value([
                'nl' => $valueNL = 'one',
                'en' => $valueEN = 'two',
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
        foreach (array_keys($this->classes) as $class) {
            $component = $class::make('xxx')->options(['one' => 'one-value', 'two' => 'two-value'])->value($value = 'one');
            $this->assertStringContainsString($value, $component->renderPreview());
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
}
