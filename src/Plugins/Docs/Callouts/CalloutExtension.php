<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Callouts;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\CommonMark\Node\Block\BlockQuote;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Node\Block\Paragraph;
use League\CommonMark\Node\Inline\Newline;
use League\CommonMark\Node\Inline\Text;
use League\CommonMark\Node\Node;

/**
 * Turns a blockquote that opens with a GitHub alert marker on its own line,
 * such as `> [!TIP]`, into a callout. Any other blockquote stays one.
 */
final class CalloutExtension implements ExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment
            ->addEventListener(DocumentParsedEvent::class, $this->convertAlerts(...))
            ->addRenderer(Callout::class, new CalloutRenderer);
    }

    private function convertAlerts(DocumentParsedEvent $event): void
    {
        $blockQuotes = [];

        foreach ($event->getDocument()->iterator() as $node) {
            if ($node instanceof BlockQuote) {
                $blockQuotes[] = $node;
            }
        }

        foreach ($blockQuotes as $blockQuote) {
            $paragraph = $blockQuote->firstChild();

            if (! $paragraph instanceof Paragraph) {
                continue;
            }

            $markerNodes = $this->markerLineNodes($paragraph);
            $type = $this->calloutType($markerNodes);

            if ($type === null) {
                continue;
            }

            foreach ($markerNodes as $markerNode) {
                $markerNode->detach();
            }

            if ($paragraph->firstChild() instanceof Newline) {
                $paragraph->firstChild()->detach();
            }

            if (! $paragraph->hasChildren()) {
                $paragraph->detach();
            }

            $callout = new Callout($type);

            foreach ($blockQuote->children() as $child) {
                $callout->appendChild($child);
            }

            $blockQuote->replaceWith($callout);
        }
    }

    /**
     * @return list<Node> The inline nodes on the paragraph's first line.
     */
    private function markerLineNodes(Paragraph $paragraph): array
    {
        $nodes = [];

        foreach ($paragraph->children() as $node) {
            if ($node instanceof Newline) {
                break;
            }

            $nodes[] = $node;
        }

        return $nodes;
    }

    /**
     * @param  list<Node>  $markerNodes
     */
    private function calloutType(array $markerNodes): ?CalloutType
    {
        $marker = '';

        foreach ($markerNodes as $node) {
            if (! $node instanceof Text) {
                return null;
            }

            $marker .= $node->getLiteral();
        }

        if (! preg_match('/^\[!([a-z]+)\]$/i', trim($marker), $matches)) {
            return null;
        }

        return CalloutType::tryFrom(strtoupper($matches[1]));
    }
}
