<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Media;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Node\Block\Paragraph;
use League\CommonMark\Node\Inline\Text;
use Thinktomorrow\Chief\Plugins\Docs\GuideNode;
use Thinktomorrow\Chief\Plugins\Docs\GuideTree;

/**
 * Shows the images and videos of a guide. A relative path is an asset next to the
 * guide's file in the same source, pointed at its admin URL. One that doesn't
 * exist, lies outside the source or isn't visible renders as its alt text. URLs
 * with a scheme and absolute paths are left as written. An image on a line of its
 * own becomes a figure.
 */
final class GuideMediaExtension implements ExtensionInterface
{
    public function __construct(
        private readonly GuideNode $guide,
        private readonly GuideTree $tree,
    ) {}

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment
            ->addEventListener(DocumentParsedEvent::class, $this->resolveMedia(...))
            ->addRenderer(Image::class, new MediaRenderer, 10)
            ->addRenderer(Figure::class, new FigureRenderer);
    }

    private function resolveMedia(DocumentParsedEvent $event): void
    {
        $images = [];

        foreach ($event->getDocument()->iterator() as $node) {
            if ($node instanceof Image) {
                $images[] = $node;
            }
        }

        foreach ($images as $image) {
            if ($this->isRelative($image->getUrl())) {
                $url = $this->assetUrl($image->getUrl());

                if ($url === null) {
                    $image->replaceWith(new Text(MediaRenderer::altText($image)));

                    continue;
                }

                $image->setUrl($url);
            }

            $paragraph = $image->parent();

            if ($paragraph instanceof Paragraph && $paragraph->firstChild() === $image && $image->next() === null) {
                $paragraph->replaceWith(new Figure($image));
            }
        }
    }

    private function isRelative(string $url): bool
    {
        return ! preg_match('/^[a-z][a-z0-9+.-]*:/i', $url) && ! str_starts_with($url, '/');
    }

    private function assetUrl(string $relativeUrl): ?string
    {
        $relativeFile = rawurldecode((string) preg_replace('/[?#].*$/s', '', $relativeUrl));
        $asset = $this->guide->location === null ? null : GuideAsset::referencedFrom($this->guide->location, $relativeFile);

        return $asset !== null && $asset->isVisibleIn($this->tree) ? $asset->url() : null;
    }
}
