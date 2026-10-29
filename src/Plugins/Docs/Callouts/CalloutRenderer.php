<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Callouts;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;

/**
 * Renders a callout with Chief's callout component.
 */
final class CalloutRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): string
    {
        Callout::assertInstanceOf($node);

        return Blade::render('<x-chief::callout :variant="$variant" :title="$title">{{ $content }}</x-chief::callout>', [
            'variant' => $node->type->variant(),
            'title' => $node->type->label(),
            'content' => new HtmlString($childRenderer->renderNodes($node->children())),
        ]);
    }
}
