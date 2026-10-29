<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Media;

use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Node\Inline\Newline;
use League\CommonMark\Node\Node;
use League\CommonMark\Node\NodeIterator;
use League\CommonMark\Node\StringContainerInterface;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use League\CommonMark\Util\RegexHelper;
use League\CommonMark\Util\Xml;
use League\Config\ConfigurationAwareInterface;
use League\Config\ConfigurationInterface;

/**
 * Renders image syntax as a lazy loaded image, or as a muted inline video when it
 * points to a video file, with the alt text as the video's fallback.
 */
final class MediaRenderer implements ConfigurationAwareInterface, NodeRendererInterface
{
    private ConfigurationInterface $config;

    public function setConfiguration(ConfigurationInterface $configuration): void
    {
        $this->config = $configuration;
    }

    public static function altText(Image $image): string
    {
        $altText = '';

        foreach (new NodeIterator($image) as $node) {
            if ($node instanceof StringContainerInterface) {
                $altText .= $node->getLiteral();
            } elseif ($node instanceof Newline) {
                $altText .= "\n";
            }
        }

        return $altText;
    }

    public function render(Node $node, ChildNodeRendererInterface $childRenderer): HtmlElement
    {
        Image::assertInstanceOf($node);

        $url = $node->getUrl();
        $source = ! $this->config->get('allow_unsafe_links') && RegexHelper::isLinkPotentiallyUnsafe($url) ? '' : $url;
        $altText = self::altText($node);

        if (AssetType::fromPath($url)?->isVideo()) {
            return new HtmlElement('video', [
                'src' => $source,
                'controls' => true,
                'muted' => true,
                'playsinline' => true,
                'preload' => 'metadata',
                'data-guide-media' => true,
            ], Xml::escape($altText));
        }

        return new HtmlElement('img', array_filter([
            'src' => $source,
            'alt' => $altText,
            'title' => $node->getTitle(),
            'loading' => 'lazy',
            'data-guide-media' => true,
        ], fn (mixed $value) => $value !== null), '', true);
    }
}
