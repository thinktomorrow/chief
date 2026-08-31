<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields;

use Carbon\CarbonImmutable;
use Thinktomorrow\Chief\Forms\Fields\Date;
use Thinktomorrow\Chief\Forms\Fields\Datetime;
use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\File;
use Thinktomorrow\Chief\Forms\Fields\Hidden;
use Thinktomorrow\Chief\Forms\Fields\Image;
use Thinktomorrow\Chief\Forms\Fields\Number;
use Thinktomorrow\Chief\Forms\Fields\Slider;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Fields\Textarea;
use Thinktomorrow\Chief\Forms\Fields\Time;
use Thinktomorrow\Chief\Forms\Tests\FormsTestCase;

class FieldAttributesFormsTest extends FormsTestCase
{
    private array $classes;

    protected function setUp(): void
    {
        parent::setUp();

        $this->classes = [
            Textarea::class,
            Text::class,
            Number::class,
            Slider::class,
            File::class,
            Image::class,
            Hidden::class,
            Date::class,
            Datetime::class,
            Time::class,
        ];
    }

    public function test_it_has_default_attributes()
    {
        foreach ($this->classes as $class) {
            /** @var Field $component */
            $component = $class::make('xxx');

            $this->assertEquals('xxx', $component->getKey());
            $this->assertEquals('xxx', $component->getId());
            $this->assertEquals('xxx', $component->getColumnName());

            if ($component instanceof File) {
                $this->assertEquals('files[xxx]', $component->getName());
            } else {
                $this->assertEquals('xxx', $component->getName());
            }
        }
    }

    public function test_it_can_use_key_with_brackets()
    {
        $component = Text::make('form[title]');

        $this->assertEquals('form[title]', $component->getKey());
        $this->assertEquals('form.title', $component->getId());
        $this->assertEquals('form[title]', $component->getName());
        $this->assertEquals('form[title]', $component->getColumnName());
    }

    public function test_min_max_and_step_survive_a_livewire_roundtrip()
    {
        $component = Date::make('xxx')->min('2026-01-01')->max('2026-12-31')->step(2);

        $revived = Date::fromLivewire($component->toLivewire());

        $this->assertEquals('2026-01-01', $revived->getMin());
        $this->assertEquals('2026-12-31', $revived->getMax());
        $this->assertEquals(2, $revived->getStep());
    }

    /**
     * A step that matches no explicit value used to be dropped on hydration, which
     * silently reverted the field to the default step of its own constructor.
     */
    public function test_a_custom_step_is_not_reverted_to_the_field_default_after_a_livewire_roundtrip()
    {
        $component = Time::make('xxx')->step(60);

        $this->assertEquals(60, Time::fromLivewire($component->toLivewire())->getStep());
    }

    public function test_fields_without_min_max_or_step_are_not_altered_by_a_livewire_roundtrip()
    {
        $revived = Text::fromLivewire(Text::make('xxx')->toLivewire());

        $this->assertEquals('xxx', $revived->getKey());
    }

    public function test_default_steps_are_set_on_construction_instead_of_via_the_factory()
    {
        $this->assertEquals(60 * 5, (new Time('xxx'))->getStep());
        $this->assertEquals(60, (new Datetime('xxx'))->getStep());

        $this->assertEquals(60 * 5, Time::make('xxx')->getStep());
        $this->assertEquals(60, Datetime::make('xxx')->getStep());
        $this->assertNull(Date::make('xxx')->getStep());
    }

    /**
     * CarbonImmutable extends DateTimeImmutable, so it is not an instance of DateTime. An
     * unformatted value reaches the input as 'Y-m-d H:i:s', which the browser rejects.
     */
    public function test_date_fields_format_immutable_date_values()
    {
        $this->assertEquals('2026-09-18', Date::make('xxx')->value(CarbonImmutable::parse('2026-09-18 20:30:00'))->getValue());
        $this->assertEquals('20:30', Time::make('xxx')->value(CarbonImmutable::parse('2026-09-18 20:30:00'))->getValue());
        $this->assertEquals('2026-09-18T20:30', Datetime::make('xxx')->value(CarbonImmutable::parse('2026-09-18 20:30:00'))->getValue());
    }
}
