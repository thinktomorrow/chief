<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Tests\Layouts;

use Thinktomorrow\Chief\Forms\Fields\Textarea;
use Thinktomorrow\Chief\Forms\Layouts\Card;
use Thinktomorrow\Chief\Tests\TestCase;

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
        $this->assertStringContainsString('<textarea', $component->toHtml());
    }
}
