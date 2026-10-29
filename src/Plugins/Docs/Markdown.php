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
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkProcessor;
use League\CommonMark\MarkdownConverter;
use Thinktomorrow\Chief\Plugins\Docs\Callouts\CalloutExtension;

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

        return (new MarkdownConverter($environment))->convert($value)->getContent();
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
