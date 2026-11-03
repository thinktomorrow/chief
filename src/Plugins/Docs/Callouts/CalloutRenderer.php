<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Callouts;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use League\CommonMark\Util\Xml;

/**
 * Renders a callout as plain HTML that a surface styles against:
 * `<aside class="guide-callout" data-callout="tip">` with a `guide-callout-title`
 * paragraph holding its label. `data-callout` is `tip`, `note` or `warning`.
 */
final class CalloutRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): HtmlElement
    {
        Callout::assertInstanceOf($node);

        $separator = $childRenderer->getInnerSeparator();

        return new HtmlElement('aside', [
            'class' => 'guide-callout',
            'data-callout' => strtolower($node->type->value),
        ], $separator
            .new HtmlElement('p', ['class' => 'guide-callout-title'], Xml::escape($node->type->label()))
            .$separator
            .$childRenderer->renderNodes($node->children())
            .$separator);
    }
}
