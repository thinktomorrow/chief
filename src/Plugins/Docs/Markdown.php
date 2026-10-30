<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs;

use Closure;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Event\DocumentParsedEvent;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\CommonMark\Node\Block\Heading;
use League\CommonMark\Extension\ExternalLink\ExternalLinkExtension;
use League\CommonMark\Extension\ExternalLink\ExternalLinkProcessor;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalink;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkProcessor;
use League\CommonMark\MarkdownConverter;
use League\CommonMark\Node\Block\AbstractBlock;
use League\CommonMark\Node\Block\Document;
use League\CommonMark\Node\Inline\Newline;
use League\CommonMark\Node\RawMarkupContainerInterface;
use League\CommonMark\Node\StringContainerHelper;
use League\CommonMark\Node\StringContainerInterface;
use League\CommonMark\Parser\MarkdownParser;
use League\CommonMark\Renderer\HtmlRenderer;
use Thinktomorrow\Chief\Plugins\Docs\Callouts\CalloutExtension;
use Thinktomorrow\Chief\Plugins\Docs\Links\GuideLinkExtension;
use Thinktomorrow\Chief\Plugins\Docs\Media\GuideMediaExtension;
use Thinktomorrow\Chief\Plugins\Docs\Search\GuideText;

class Markdown
{
    /** @var list<Closure(Environment): mixed> */
    private static array $configureCallbacks = [];

    /**
     * Extend how guides are rendered, typically from a service provider.
     *
     * @param  Closure(Environment): mixed  $callback
     */
    public static function configureUsing(Closure $callback): void
    {
        self::$configureCallbacks[] = $callback;
    }

    public static function flushConfigureCallbacks(): void
    {
        self::$configureCallbacks = [];
    }

    public function convert(?string $value): ?string
    {
        if (! $value) {
            return null;
        }

        return $this->render($value, $this->environment());
    }

    /**
     * Render a guide's body, pointing its links to other guides and its images and videos
     * at those the current admin may see.
     */
    public function renderGuide(GuideNode $guide, GuideTree $tree): RenderedGuide
    {
        if (! $guide->markdown) {
            return new RenderedGuide('', []);
        }

        $environment = $this->environment();
        $environment->addExtension(new GuideLinkExtension($guide, $tree));
        $environment->addExtension(new GuideMediaExtension($guide, $tree));

        $document = (new MarkdownParser($environment))->parse($guide->markdown);

        return new RenderedGuide(
            (new HtmlRenderer($environment))->renderDocument($document)->getContent(),
            $this->headings($document),
        );
    }

    /**
     * The plain text of a guide's body without raw HTML, with its `##` and `###` headings, as search reads it.
     */
    public function guideText(GuideNode $guide): GuideText
    {
        if (! $guide->markdown) {
            return new GuideText('', []);
        }

        $document = (new MarkdownParser($this->environment()))->parse($guide->markdown);
        $body = '';

        foreach ($document->iterator() as $node) {
            if ($node instanceof StringContainerInterface && ! $node instanceof RawMarkupContainerInterface) {
                $body .= $node->getLiteral();
            } elseif ($node instanceof AbstractBlock || $node instanceof Newline) {
                $body .= ' ';
            }
        }

        return new GuideText(trim((string) preg_replace('/\s+/u', ' ', $body)), $this->headings($document, maxLevel: 3));
    }

    private function environment(): Environment
    {
        $environment = new Environment([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
            'slug_normalizer' => [
                'instance' => new HeadingSlugNormalizer,
            ],
            'heading_permalink' => [
                'min_heading_level' => 2,
                'max_heading_level' => 3,
                'insert' => HeadingPermalinkProcessor::INSERT_AFTER,
                'apply_id_to_heading' => true,
                'id_prefix' => '',
                'fragment_prefix' => '',
                'html_class' => 'heading-permalink',
                'title' => 'Link naar deze sectie',
                'symbol' => '#',
            ],
            'external_link' => [
                'internal_hosts' => '/^'.preg_quote((string) parse_url(url('/'), PHP_URL_HOST), '/').'$/i',
                'open_in_new_window' => true,
                'noopener' => ExternalLinkProcessor::APPLY_EXTERNAL,
                'noreferrer' => ExternalLinkProcessor::APPLY_NONE,
            ],
        ]);

        $environment->addExtension(new CommonMarkCoreExtension);
        $environment->addExtension(new GithubFlavoredMarkdownExtension);
        $environment->addExtension(new FrontMatterExtension);
        $environment->addExtension(new HeadingPermalinkExtension);
        $environment->addExtension(new ExternalLinkExtension);
        $environment->addExtension(new CalloutExtension);
        $environment->addEventListener(DocumentParsedEvent::class, $this->removeFirstTitle(...));

        foreach (self::$configureCallbacks as $callback) {
            $callback($environment);
        }

        return $environment;
    }

    private function render(string $markdown, Environment $environment): string
    {
        return (new MarkdownConverter($environment))->convert($markdown)->getContent();
    }

    /**
     * @return list<GuideHeading> The headings from `##` down to the given level, in document order.
     */
    private function headings(Document $document, int $maxLevel = 2): array
    {
        $headings = [];

        foreach ($document->iterator() as $node) {
            if ($node instanceof Heading && $node->getLevel() >= 2 && $node->getLevel() <= $maxLevel && $node->data->has('attributes/id')) {
                $headings[] = new GuideHeading(
                    $node->data->get('attributes/id'),
                    StringContainerHelper::getChildText($node, [HeadingPermalink::class]),
                );
            }
        }

        return $headings;
    }

    /**
     * The guide page shows the title itself, so the body's first top-level H1 is not repeated.
     */
    private function removeFirstTitle(DocumentParsedEvent $event): void
    {
        foreach ($event->getDocument()->children() as $node) {
            if ($node instanceof Heading && $node->getLevel() === 1) {
                $node->detach();

                return;
            }
        }
    }
}
