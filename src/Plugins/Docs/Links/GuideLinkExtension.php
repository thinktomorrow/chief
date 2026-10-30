<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Links;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Extension\ExtensionInterface;
use Thinktomorrow\Chief\Plugins\Docs\GuideNode;
use Thinktomorrow\Chief\Plugins\Docs\GuideTree;

/**
 * Points links between guides at their page in the admin. A target ending in
 * `.md` is a file relative to the current guide's file in the same source, and
 * a target with `::` is a namespaced guide ID. Any other link is left alone.
 * A link to a guide outside the visible tree is rendered as plain text.
 */
final class GuideLinkExtension implements ExtensionInterface
{
    public function __construct(
        private readonly GuideNode $guide,
        private readonly GuideTree $tree,
    ) {}

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addEventListener(DocumentParsedEvent::class, $this->resolveLinks(...));
    }

    private function resolveLinks(DocumentParsedEvent $event): void
    {
        $links = [];

        foreach ($event->getDocument()->iterator() as $node) {
            if ($node instanceof Link) {
                $links[] = $node;
            }
        }

        foreach ($links as $link) {
            $target = GuideLinkTarget::parse($link->getUrl());

            if ($target === null) {
                continue;
            }

            $guideId = $target->guideId($this->guide);
            $guide = $guideId === null ? null : $this->tree->find($guideId);

            if ($guide === null || ! $guide->hasPage()) {
                $this->unwrap($link);

                continue;
            }

            $link->setUrl(route('chief.docs.show', $guide->id).$target->fragment);
        }
    }

    private function unwrap(Link $link): void
    {
        foreach ($link->children() as $child) {
            $link->insertBefore($child);
        }

        $link->detach();
    }
}
