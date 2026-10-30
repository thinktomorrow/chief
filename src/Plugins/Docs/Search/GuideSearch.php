<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Search;

use Illuminate\Support\Arr;
use Thinktomorrow\Chief\Plugins\Docs\GuideHeading;
use Thinktomorrow\Chief\Plugins\Docs\GuideNode;
use Thinktomorrow\Chief\Plugins\Docs\GuideTree;
use Thinktomorrow\Chief\Plugins\Docs\Markdown;

/**
 * Finds the guides of a visible guide tree that contain every word of a query
 * in their title, description, headings or body. Words match as case- and
 * diacritic-insensitive substrings, without stemming.
 */
final class GuideSearch
{
    public const MIN_QUERY_LENGTH = 2;

    public const MAX_RESULTS = 8;

    public function __construct(
        private readonly Markdown $markdown,
    ) {}

    /**
     * Guides whose title matches the whole query come first, then the others,
     * each in the sidebar's order.
     *
     * @return list<GuideSearchResult>
     */
    public function search(GuideTree $tree, string $query): array
    {
        $words = $this->words($query);

        if ($words === []) {
            return [];
        }

        /** @var list<array{guide: GuideNode, text: GuideText}> $titleMatches */
        $titleMatches = [];

        /** @var list<array{guide: GuideNode, text: GuideText}> $otherMatches */
        $otherMatches = [];

        foreach ($tree->readingOrder() as $guide) {
            $text = $this->markdown->guideText($guide);
            $searchable = FoldedText::fold($guide->title."\n".$guide->description."\n".$text->body);

            foreach ($words as $word) {
                if (! str_contains($searchable, $word)) {
                    continue 2;
                }
            }

            if ((new FoldedText($guide->title))->countMatching($words) === count($words)) {
                $titleMatches[] = ['guide' => $guide, 'text' => $text];
            } else {
                $otherMatches[] = ['guide' => $guide, 'text' => $text];
            }
        }

        return array_map(
            fn (array $match) => $this->result($tree, $match['guide'], $match['text'], $words),
            array_slice([...$titleMatches, ...$otherMatches], 0, self::MAX_RESULTS),
        );
    }

    public static function isTooShort(string $query): bool
    {
        return mb_strlen(trim($query)) < self::MIN_QUERY_LENGTH;
    }

    /**
     * @return list<string> The query's folded words, or none for a query that is too short.
     */
    private function words(string $query): array
    {
        if (self::isTooShort($query)) {
            return [];
        }

        return array_values(array_unique(preg_split('/\s+/u', FoldedText::fold($query), flags: PREG_SPLIT_NO_EMPTY) ?: []));
    }

    /**
     * @param  list<string>  $words
     */
    private function result(GuideTree $tree, GuideNode $guide, GuideText $text, array $words): GuideSearchResult
    {
        $body = new FoldedText($text->body);
        $heading = $this->bestMatchingHeading($text->headings, $words);

        return new GuideSearchResult(
            section: Arr::last($tree->ancestors($guide))?->title,
            title: $guide->title,
            snippet: $guide->description && $body->countMatching($words) === 0
                ? (new FoldedText($guide->description))->excerpt($words)
                : $body->excerpt($words),
            url: route('chief.docs.show', $guide->id).($heading ? '#'.$heading->id : ''),
        );
    }

    /**
     * The heading that contains the most query words, the first one on a tie.
     *
     * @param  list<GuideHeading>  $headings
     * @param  list<string>  $words
     */
    private function bestMatchingHeading(array $headings, array $words): ?GuideHeading
    {
        $best = null;
        $bestCount = 0;

        foreach ($headings as $heading) {
            $count = (new FoldedText($heading->title))->countMatching($words);

            if ($count > $bestCount) {
                $best = $heading;
                $bestCount = $count;
            }
        }

        return $best;
    }
}
