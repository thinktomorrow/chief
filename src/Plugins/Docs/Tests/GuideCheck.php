<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Tests;

use Illuminate\Support\Facades\File;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Parser\MarkdownParser;
use Thinktomorrow\Chief\Plugins\Docs\GuideHeading;
use Thinktomorrow\Chief\Plugins\Docs\GuideNode;
use Thinktomorrow\Chief\Plugins\Docs\GuideTree;
use Thinktomorrow\Chief\Plugins\Docs\Links\GuideLinkTarget;
use Thinktomorrow\Chief\Plugins\Docs\Markdown;
use Thinktomorrow\Chief\Plugins\Docs\Media\GuideAsset;

/**
 * Lists what would break in the guides of a full guide tree: front matter
 * without title, description or order, an unknown parent, a link or anchor
 * that doesn't resolve and a relative image that doesn't exist.
 */
final class GuideCheck
{
    private MarkdownParser $parser;

    public function __construct(private readonly Markdown $markdown)
    {
        $environment = new Environment;
        $environment->addExtension(new CommonMarkCoreExtension);
        $environment->addExtension(new GithubFlavoredMarkdownExtension);

        $this->parser = new MarkdownParser($environment);
    }

    /**
     * @return list<string> One line per problem, prefixed by the guide's ID.
     */
    public function problems(GuideTree $tree): array
    {
        $problems = [];

        foreach ($this->guides($tree, $tree->roots()) as $guide) {
            foreach ([...$this->frontMatterProblems($guide, $tree), ...$this->bodyProblems($guide, $tree)] as $problem) {
                $problems[] = $guide->id.': '.$problem;
            }
        }

        return $problems;
    }

    /**
     * @param  list<GuideNode>  $nodes
     * @return list<GuideNode> The given nodes and their descendants that have a page.
     */
    private function guides(GuideTree $tree, array $nodes): array
    {
        $guides = [];

        foreach ($nodes as $node) {
            $guides = [...$guides, ...($node->hasPage() ? [$node] : []), ...$this->guides($tree, $tree->children($node))];
        }

        return $guides;
    }

    /**
     * The tree falls back to the first H1 for a missing title and drops an
     * unknown parent, so the front matter is read from the guide's file.
     *
     * @return list<string>
     */
    private function frontMatterProblems(GuideNode $guide, GuideTree $tree): array
    {
        $source = $guide->location->source;
        $file = $source->guideFile($source->relativeIdOf($guide->location->path), $guide->location->path);
        $frontMatter = (new FrontMatterExtension)->getFrontMatterParser()->parse(File::get($file))->getFrontMatter();
        $frontMatter = is_array($frontMatter) ? $frontMatter : [];

        $problems = [];

        foreach (['title', 'description'] as $key) {
            if (! is_string($frontMatter[$key] ?? null) || trim($frontMatter[$key]) === '') {
                $problems[] = 'missing '.$key;
            }
        }

        if (! is_int($frontMatter['order'] ?? null)) {
            $problems[] = 'missing order';
        }

        if (isset($frontMatter['parent']) && (! is_string($frontMatter['parent']) || $tree->find($frontMatter['parent']) === null)) {
            $problems[] = 'unknown parent ['.json_encode($frontMatter['parent']).']';
        }

        return $problems;
    }

    /**
     * @return list<string>
     */
    private function bodyProblems(GuideNode $guide, GuideTree $tree): array
    {
        $problems = [];

        foreach ($this->parser->parse($guide->markdown)->iterator() as $node) {
            if ($node instanceof Link) {
                $problems = [...$problems, ...$this->linkProblems($node->getUrl(), $guide, $tree)];
            } elseif ($node instanceof Image && $this->isRelative($node->getUrl())) {
                $relativeFile = rawurldecode((string) preg_replace('/[?#].*$/s', '', $node->getUrl()));

                if (GuideAsset::referencedFrom($guide->location, $relativeFile)?->file() === null) {
                    $problems[] = 'missing image ['.$node->getUrl().']';
                }
            }
        }

        return $problems;
    }

    /**
     * @return list<string>
     */
    private function linkProblems(string $url, GuideNode $guide, GuideTree $tree): array
    {
        if (str_starts_with($url, '#')) {
            return $this->hasAnchor($guide, substr($url, 1)) ? [] : ['unknown anchor ['.$url.']'];
        }

        $target = GuideLinkTarget::parse($url);

        if ($target === null) {
            return [];
        }

        $targetGuideId = $target->guideId($guide);
        $targetGuide = $targetGuideId === null ? null : $tree->find($targetGuideId);

        if ($targetGuide === null || ! $targetGuide->hasPage()) {
            return ['broken link ['.$url.']'];
        }

        if ($target->fragment !== '' && ! $this->hasAnchor($targetGuide, substr($target->fragment, 1))) {
            return ['unknown anchor ['.$url.']'];
        }

        return [];
    }

    private function hasAnchor(GuideNode $guide, string $anchor): bool
    {
        $headingIds = array_map(fn (GuideHeading $heading) => $heading->id, $this->markdown->guideText($guide)->headings);

        return in_array($anchor, $headingIds, true);
    }

    private function isRelative(string $url): bool
    {
        return ! preg_match('/^[a-z][a-z0-9+.-]*:/i', $url) && ! str_starts_with($url, '/');
    }
}
