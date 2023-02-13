<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Forms\Layouts;

use Thinktomorrow\Chief\Forms\Fields\Textarea;
use Thinktomorrow\Chief\Forms\Layouts\Grid;
use Thinktomorrow\Chief\Tests\TestCase;

class GridTest extends TestCase
{
    /** @test */
    public function it_can_render_a_grid_component()
    {
        $component = Grid::make()->components([
            Textarea::make('intro'),
        ]);

        $this->assertStringStartsWith('<div ', $component->toHtml());
        $this->assertStringEndsWith("</div>\n", $component->toHtml());
        $this->assertStringContainsString('<textarea', $component->toHtml());
    }

    /** @test */
    public function it_can_create_columns()
    {
        $component = Grid::make()->columns(2)->components([
            Textarea::make('intro'),
        ]);

        $this->assertEquals(2, $component->getColumns());
        $this->assertStringContainsString('sm:w-1/2', $component->toHtml());
    }
}
