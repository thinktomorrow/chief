<?php

namespace Thinktomorrow\Chief\Tests\Unit\Forms\Fields\Views;

use Thinktomorrow\Chief\Forms\Fields\Field;
use Thinktomorrow\Chief\Forms\Fields\Repeat;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Tests\Unit\Forms\TestCase;

class RenderRepeatFieldTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_render_a_repeat_field()
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

        $render = $component->toHtml();

        $this->assertStringContainsString('name="xxx[0][title]"', $render);
        $this->assertStringContainsString('name="xxx[0][function]"', $render);
        $this->assertStringContainsString('name="xxx[1][title]"', $render);
        $this->assertStringContainsString('name="xxx[1][function]"', $render);
    }

    /** @test */
    public function it_can_render_a_repeat_field_with_localized_content()
    {
        $component = Repeat::make('xxx')->items([
            Text::make('title')->locales(['nl','en']),
        ])->value([
            [
                'title' => ['nl' => 'first title nl', 'en' => 'first title en'],
            ],
            [
                'title' => ['nl' => 'second title nl', 'en' => 'second title en'],
            ],
        ]);

        $render = $component->toHtml();

        $this->assertStringContainsString('name="xxx[0][title][nl]"', $render);
        $this->assertStringContainsString('value="first title nl"', $render);
        $this->assertStringContainsString('name="xxx[0][title][en]"', $render);
        $this->assertStringContainsString('value="first title en"', $render);
    }
}
