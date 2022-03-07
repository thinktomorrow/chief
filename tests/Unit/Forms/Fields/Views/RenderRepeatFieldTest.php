<?php

namespace Thinktomorrow\Chief\Tests\Unit\Forms\Fields\Views;

use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\Repeat;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Tests\TestCase;

class RenderRepeatFieldTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->markTestIncomplete();
    }

    /** @test */
    public function it_can_render_all_fields()
    {
        $component = Repeat::make('xxx')->items([
            Text::make('title'),
            Text::make('function'),
        ])->value([
            [
                'title' => 'first title',
                'function' => 'writer',
            ],
            [
                'title' => 'second title',
                'function' => 'author',
            ],
        ]);
        trap($component->toHtml());
        $this->assertStringContainsString('name="xxx"', $component->toHtml());
    }

    /** @test */
    public function it_can_render_localized_fields()
    {
        /** @var Field $class */
        foreach (array_keys($this->classes) as $class) {
            $component = $class::make('xxx')->locales(['nl', 'en'])->value([
                'nl' => $valueNL = 'value-nl',
                'en' => $valueEN = 'value-en',
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
            $component = $class::make('xxx')->displayInWindow()->value($value = 'given value');
            $this->assertStringContainsString($value, $component->toHtml());
        }
    }

    /** @test */
    public function it_can_render_a_custom_view()
    {
        $this->app['view']->addNamespace('test-views', __DIR__.'/../../stubs/views');

        $this->assertStringContainsString(
            'this is a custom field view',
            Text::make('xxx')->view('test-views::custom-field')->render()
        );
    }
}
