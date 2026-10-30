<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use League\CommonMark\Extension\FrontMatter\FrontMatterExtension;
use League\CommonMark\Extension\FrontMatter\FrontMatterParserInterface;

/**
 * Reads the guides of all doc sources and merges them into one guide tree.
 */
final class GuideTreeBuilder
{
    private FrontMatterParserInterface $frontMatterParser;

    public function __construct()
    {
        $this->frontMatterParser = (new FrontMatterExtension)->getFrontMatterParser();
    }

    public function build(DocSources $sources): GuideTree
    {
        /** @var array<string, GuideNode> $nodes */
        $nodes = [];

        /** @var array<string, null|string> $parentIds */
        $parentIds = [];

        foreach ($sources->all() as $source) {
            $this->readSource($source, $nodes, $parentIds);
        }

        $parentIds = $this->withoutUnresolvableParents($parentIds);

        return new GuideTree($nodes, $parentIds, $this->sortedChildIds($nodes, $parentIds));
    }

    /**
     * @param  array<string, GuideNode>  $nodes
     * @param  array<string, null|string>  $parentIds
     */
    private function readSource(DocSource $source, array &$nodes, array &$parentIds): void
    {
        if (! is_dir($source->path)) {
            return;
        }

        /** @var array<string, true> $folders */
        $folders = [];

        foreach (File::allFiles($source->path) as $file) {
            $relativePath = str_replace('\\', '/', $file->getRelativePathname());

            if ($file->getExtension() !== 'md' || $source->isExcluded($relativePath)) {
                continue;
            }

            $relativeId = $source->relativeIdOf($relativePath);

            foreach ($this->foldersOf($relativeId) as $folder) {
                $folders[$folder] = true;
            }

            $input = $this->frontMatterParser->parse(File::get($source->guideFile($relativeId, $relativePath)));
            $frontMatter = is_array($input->getFrontMatter()) ? $input->getFrontMatter() : [];
            $markdown = $input->getContent();

            $id = $source->guideId($relativeId);

            $nodes[$id] = new GuideNode(
                id: $id,
                title: $this->stringValue($frontMatter, 'title')
                    ?? $this->firstHeading($markdown)
                    ?? Str::headline(basename($relativeId)),
                description: $this->stringValue($frontMatter, 'description'),
                order: is_int($frontMatter['order'] ?? null) ? $frontMatter['order'] : null,
                markdown: $markdown,
                roles: $this->roles($frontMatter),
                location: new GuideLocation($source, $relativePath),
            );

            $parentIds[$id] = $this->stringValue($frontMatter, 'parent') ?? $this->folderId($source, $relativeId);
        }

        foreach (array_keys($folders) as $folder) {
            $id = $source->guideId($folder);

            if (isset($nodes[$id])) {
                continue;
            }

            $nodes[$id] = new GuideNode(id: $id, title: Str::headline(basename($folder)));
            $parentIds[$id] = $this->folderId($source, $folder);
        }
    }

    /**
     * @return list<string> Every folder that contains the given ID, outermost first.
     */
    private function foldersOf(string $relativeId): array
    {
        $segments = explode('/', $relativeId);
        array_pop($segments);

        $folders = [];

        foreach ($segments as $index => $segment) {
            $folders[] = implode('/', array_slice($segments, 0, $index + 1));
        }

        return $folders;
    }

    /**
     * The ID of the section folder that holds the given ID, or null at the source root.
     */
    private function folderId(DocSource $source, string $relativeId): ?string
    {
        return str_contains($relativeId, '/') ? $source->guideId(dirname($relativeId)) : null;
    }

    private function firstHeading(string $markdown): ?string
    {
        if (preg_match('/^#[ \t]+(.+?)[ \t#]*$/m', $markdown, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $frontMatter
     */
    private function stringValue(array $frontMatter, string $key): ?string
    {
        $value = $frontMatter[$key] ?? null;

        return is_scalar($value) && (string) $value !== '' ? (string) $value : null;
    }

    /**
     * A single role may be written as text. A role that is not a non-empty text
     * becomes an empty role name, which no admin has, so a malformed value
     * restricts the guide instead of opening it to everyone.
     *
     * @param  array<string, mixed>  $frontMatter
     * @return list<string>
     */
    private function roles(array $frontMatter): array
    {
        $value = $frontMatter['roles'] ?? [];

        return array_map(
            fn (mixed $role) => is_scalar($role) ? (string) $role : '',
            is_array($value) ? array_values($value) : [$value],
        );
    }

    /**
     * A parent that does not exist, or that leads back to the node itself, puts the node at the top level.
     *
     * @param  array<string, null|string>  $parentIds
     * @return array<string, null|string>
     */
    private function withoutUnresolvableParents(array $parentIds): array
    {
        $parentIds = array_map(fn (?string $parentId) => $parentId !== null && array_key_exists($parentId, $parentIds) ? $parentId : null, $parentIds);

        $cyclicIds = [];

        foreach (array_keys($parentIds) as $id) {
            $visited = [];
            $current = $id;

            while ($current !== null && ! isset($visited[$current])) {
                $visited[$current] = true;
                $current = $parentIds[$current];
            }

            if ($current === $id) {
                $cyclicIds[] = $id;
            }
        }

        foreach ($cyclicIds as $id) {
            $parentIds[$id] = null;
        }

        return $parentIds;
    }

    /**
     * Siblings sort by order, then by title. Guides without an order come last.
     *
     * @param  array<string, GuideNode>  $nodes
     * @param  array<string, null|string>  $parentIds
     * @return array<string, list<string>>
     */
    private function sortedChildIds(array $nodes, array $parentIds): array
    {
        $siblings = array_values($nodes);

        usort($siblings, fn (GuideNode $a, GuideNode $b) => [$a->order === null, $a->order] <=> [$b->order === null, $b->order]
            ?: strnatcasecmp($a->title, $b->title));

        $childIds = [];

        foreach ($siblings as $node) {
            $childIds[$parentIds[$node->id] ?? ''][] = $node->id;
        }

        return $childIds;
    }
}
