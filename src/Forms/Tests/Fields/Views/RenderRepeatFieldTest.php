<?php

namespace Thinktomorrow\Chief\Forms\Tests\Fields\Views;

use Thinktomorrow\Chief\Forms\Fields\Repeat;
use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Tests\ChiefTestCase;

class RenderRepeatFieldTest extends ChiefTestCase
{
    protected function setUp(): void
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
            Text::make('title')->locales(['nl', 'en']),
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

    /** @test */
    public function when_repeat_field_misses_locale_it_shows_default()
    {
        $component = Repeat::make('xxx')->items([
            Text::make('title')->locales(['nl', 'fr']),
        ])->value([
            [
                'title' => ['nl' => 'first title nl'],
            ],
            [
                'title' => ['nl' => 'second title nl'],
            ],
        ]);

        $render = $component->toHtml();

        $this->assertStringContainsString('name="xxx[0][title][nl]"', $render);
        $this->assertStringContainsString('value="first title nl"', $render);
        $this->assertStringContainsString('name="xxx[0][title][fr]"', $render);
        $this->assertStringContainsString('value=""', $render);
    }

    /** @test */
    public function it_can_render_a_nested_repeat_field()
    {
        $component = Repeat::make('xxx')->items([
            Text::make('title'),
            Repeat::make('yyy')->items([
                Text::make('function'),
            ]),
        ])->value([
            [
                'title' => 'first title',
                'yyy' => [
                    ['function' => 'aaa'],
                    ['function' => 'bbb'],
                ],
            ],
            [
                'title' => 'second title',
                'yyy' => [
                    ['function' => 'ccc'],
                ],
            ],
        ]);

        $render = $component->toHtml();

        $this->assertStringContainsString('name="xxx[0][title]"', $render);
        $this->assertStringContainsString('name="xxx[0][yyy][0][function]"', $render);
        $this->assertStringContainsString('name="xxx[0][yyy][1][function]"', $render);
        $this->assertStringContainsString('name="xxx[1][title]"', $render);
        $this->assertStringContainsString('name="xxx[1][yyy][0][function]"', $render);
    }

    /** @test */
    public function it_can_render_a_default_empty_nested_repeat_field()
    {
        $component = Repeat::make('xxx')->items([
            Text::make('title'),
            Repeat::make('yyy')->items([
                Text::make('function'),
            ]),
        ]);

        $render = $component->toHtml();

        $this->assertStringContainsString('name="xxx[0][title]"', $render);
        $this->assertStringContainsString('name="xxx[0][yyy][0][function]"', $render);
    }
}
