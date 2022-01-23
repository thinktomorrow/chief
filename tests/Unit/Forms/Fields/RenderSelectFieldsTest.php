<?php

namespace Thinktomorrow\Chief\Tests\Unit\Forms\Fields;

use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\Radio;
use Thinktomorrow\Chief\Forms\Fields\Checkbox;
use Thinktomorrow\Chief\Forms\Fields\MultiSelect;
use Thinktomorrow\Chief\Forms\Fields\Select;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class RenderSelectFieldsTest extends TestCase
{
    private array $classes;

    public function setUp(): void
    {
        parent::setUp();

        $this->classes = [
            Select::class => 'one',
            MultiSelect::class => 'two',
            Radio::class => 'one',
            Checkbox::class => 'two',
        ];
    }

    /** @test */
    public function it_can_render_all_fields()
    {
        /** @var Field $class */
        foreach ($this->classes as $class => $value) {
            $component = $class::make('xxx')->options(['one' => 'one-value', 'two' => 'two-value'])->value($value);
            $this->assertStringContainsString('name="xxx"', $component->toHtml());
            $this->assertStringContainsString($value, $component->toHtml());
        }
    }

    /** @test */
    public function it_can_render_localized_fields()
    {
        /** @var Field $class */
        foreach (array_keys($this->classes) as $class) {
            $component = $class::make('xxx')->options(['one' => 'one-value', 'two' => 'two-value'])->locales(['nl', 'en'])->value([
                'nl' => $valueNL = 'one',
                'en' => $valueEN = 'two',
            ]);

            $render = $component->toHtml();

            $this->assertStringContainsString('trans[nl][xxx]', $render);
            $this->assertStringContainsString('trans[en][xxx]', $render);
            $this->assertStringContainsString($valueNL, $render);
            $this->assertStringContainsString($valueEN, $render);
        }
    }

    /** @test */
    public function it_can_render_all_fields_in_a_window()
    {
        /** @var Field $class */
        foreach (array_keys($this->classes) as $class) {
            $component = $class::make('xxx')->options(['one' => 'one-value', 'two' => 'two-value'])->displayInWindow()->value($value = 'one');
            $this->assertStringContainsString($value, $component->toHtml());
        }
    }

    /** @test */
    public function it_can_render_a_custom_view()
    {
        $this->app['view']->addNamespace('test-views', __DIR__.'/../stubs/views');

        $this->assertStringContainsString(
            'this is a custom field view',
            Text::make('xxx')->view('test-views::custom-field')->render()
        );
    }
}
