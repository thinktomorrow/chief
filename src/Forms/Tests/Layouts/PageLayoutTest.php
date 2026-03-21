<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Tests\Layouts;

use Thinktomorrow\Chief\Forms\Fields\Text;
use Thinktomorrow\Chief\Forms\Layouts\Form;
use Thinktomorrow\Chief\Forms\Layouts\Grid;
use Thinktomorrow\Chief\Forms\Layouts\PageLayout;
use Thinktomorrow\Chief\Forms\Tests\FormsTestCase;

class PageLayoutTest extends FormsTestCase
{
    public function test_it_keeps_grid_between_wandering_fields(): void
    {
        $layout = PageLayout::make([
            Text::make('title'),
            Grid::make('meta-grid')->items([
                Text::make('meta_left'),
                Text::make('meta_right'),
            ]),
            Text::make('subtitle'),
        ]);

        $this->assertSame(
            ['title', 'meta-grid', 'subtitle'],
            $layout->getComponentsWithoutForms()->map(fn ($component) => $component->getKey())->all()
        );

        $this->assertEquals(
            [Form::class, Grid::class, Form::class],
            array_map(fn ($component) => $component::class, $layout->getComponents())
        );
    }
}
