<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\Views;

use Carbon\Carbon;
use Thinktomorrow\Chief\Forms\Fields\Date;
use Thinktomorrow\Chief\Forms\Fields\Datetime;
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
            Datetime::class => '2022-02-02T09:23',
            Time::class => '9:23',
            Hidden::class => 'given value',
        ];
    }

    public function test_it_can_render_all_fields()
    {
        /** @var Field $class */
        foreach ($this->classes as $class => $value) {
            $component = $class::make('xxx')->value($value);
            $this->assertStringContainsString('wire:model="form.xxx"', $component->toHtml());
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

            $this->assertStringContainsString('wire:model="form.xxx.nl"', $render);
            $this->assertStringContainsString('wire:model="form.xxx.en"', $render);
        }
    }

    public function test_it_can_render_all_fields_in_a_window()
    {
        /** @var Field $class */
        foreach ($this->classes as $class => $value) {
            $component = $class::make('xxx')->value($value);

            if ($component instanceof Hidden) {
                $this->assertStringNotContainsString($value, $component->renderPreview());
            } elseif ($component instanceof Datetime) {
                $this->assertStringContainsString('02/02/2022 09:23', $component->renderPreview());
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

    public function test_localized_text_redactor_options_reference_the_localized_toolbar()
    {
        $component = Text::make('xxx')
            ->elementId('xxx_id')
            ->locales(['nl'])
            ->redactorOptions(['buttons' => ['bold']]);

        $this->assertSame(
            '#js-external-editor-toolbar-xxx_id_nl',
            $component->getRedactorOptions('nl')['toolbarExternal']
        );

        $render = $component->toHtml();

        $this->assertStringContainsString('id="js-external-editor-toolbar-xxx_id_nl"', $render);
        $this->assertStringContainsString('&quot;toolbarExternal&quot;:&quot;#js-external-editor-toolbar-xxx_id_nl&quot;', $render);
    }

    public function test_number_field_accepts_floats()
    {
        $numberField = Number::make('number')->step(0.2)->value(2.5);

        $this->assertEquals(2.5, $numberField->getValue());
        $this->assertEquals(0.2, $numberField->getStep());
    }

    /**
     * A datetime-local input only accepts a T as date and time separator. Without
     * it the browser considers the value invalid and renders an empty input.
     */
    public function test_datetime_field_formats_datetime_values_with_a_t_separator()
    {
        $datetimeField = Datetime::make('start_at')->value(Carbon::parse('2026-09-18 20:30:00'));

        $this->assertEquals('2026-09-18T20:30', $datetimeField->getValue());
        $this->assertEquals(60, $datetimeField->getStep());
    }

    public function test_datetime_field_passes_string_values_through_untouched()
    {
        $datetimeField = Datetime::make('start_at')->value('2026-09-18T20:30');

        $this->assertEquals('2026-09-18T20:30', $datetimeField->getValue());
    }

    public function test_it_omits_min_max_and_step_attributes_when_they_are_not_configured()
    {
        $render = Date::make('xxx')->value('2022-02-02')->toHtml();

        $this->assertStringNotContainsString('min=""', $render);
        $this->assertStringNotContainsString('max=""', $render);
        $this->assertStringNotContainsString('step=""', $render);
    }

    public function test_it_renders_min_and_max_attributes_when_they_are_configured()
    {
        $render = Date::make('xxx')->min('2026-01-01')->max('2026-12-31')->toHtml();

        $this->assertStringContainsString('min="2026-01-01"', $render);
        $this->assertStringContainsString('max="2026-12-31"', $render);
    }

    /**
     * A placeholder has no effect on a date, time or datetime-local input.
     */
    public function test_it_does_not_render_a_placeholder_on_date_and_time_fields()
    {
        foreach ([Date::class, Time::class, Datetime::class] as $class) {
            preg_match('/<input[^>]*>/s', $class::make('xxx')->toHtml(), $matches);

            $this->assertStringNotContainsString('placeholder', $matches[0]);
        }
    }

    public function test_previews_render_the_raw_value_when_it_cannot_be_parsed_as_a_date()
    {
        foreach ([Date::class, Time::class, Datetime::class] as $class) {
            $this->assertStringContainsString('not-a-date', $class::make('xxx')->value('not-a-date')->renderPreview());
        }
    }

    public function test_previews_render_a_placeholder_for_an_empty_value()
    {
        foreach ([Date::class, Time::class, Datetime::class] as $class) {
            $this->assertStringContainsString('...', $class::make('xxx')->value(null)->renderPreview());
        }
    }
}
