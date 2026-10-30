<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Media;

use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use League\CommonMark\Util\Xml;

/**
 * Renders a figure with the image's alt text as its caption. A video has no
 * caption, as its alt text is the fallback inside the video element.
 */
final class FigureRenderer implements NodeRendererInterface
{
    public function render(Node $node, ChildNodeRendererInterface $childRenderer): HtmlElement
    {
        Figure::assertInstanceOf($node);

        $content = $childRenderer->renderNodes($node->children());
        $altText = MediaRenderer::altText($node->media);

        if ($altText !== '' && ! AssetType::fromPath($node->media->getUrl())?->isVideo()) {
            $content .= new HtmlElement('figcaption', [], Xml::escape($altText));
        }

        return new HtmlElement('figure', [], $content);
    }
}
