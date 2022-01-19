<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Forms\Layouts;

use Thinktomorrow\Chief\Tests\TestCase;
use Thinktomorrow\Chief\Forms\Layouts\Card;
use Thinktomorrow\Chief\Forms\Fields\Textarea;

class CardTest extends TestCase
{
    /** @test */
    public function it_can_render_a_grid_component()
    {
        $component = Card::make()->components([
            Textarea::make('intro'),
        ]);

        $this->assertStringStartsWith('<div ', $component->toHtml());
        $this->assertStringEndsWith("</div>\n", $component->toHtml());
        $this->assertStringContainsString('<textarea ', $component->toHtml());
    }
}
