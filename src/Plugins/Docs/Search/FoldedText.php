<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Search;

use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

/**
 * Text folded to lowercase ASCII for matching, which keeps track of where each
 * folded character came from so matches can be marked in the original text.
 */
final class FoldedText
{
    /** @var list<string> */
    private array $characters = [];

    private string $folded = '';

    /** @var list<int> The original character index of each folded byte. */
    private array $characterIndexes = [];

    public function __construct(string $text)
    {
        foreach (mb_str_split($text) as $index => $character) {
            $foldedCharacter = self::fold($character);

            $this->characters[] = $character;
            $this->folded .= $foldedCharacter;

            for ($byte = 0; $byte < strlen($foldedCharacter); $byte++) {
                $this->characterIndexes[] = $index;
            }
        }
    }

    /**
     * Lowercase ASCII, so `Pagina's` and `café` match `pagina` and `cafe`.
     * Each character is folded on its own, as the original positions require.
     */
    public static function fold(string $text): string
    {
        return strtolower((string) preg_replace_callback('/[^\x00-\x7F]/u', fn (array $matches) => Str::ascii($matches[0]), $text));
    }

    /**
     * @param  list<string>  $words  Folded words.
     */
    public function countMatching(array $words): int
    {
        return count(array_filter($words, fn (string $word) => str_contains($this->folded, $word)));
    }

    /**
     * About `$length` characters around the first match, cut at word boundaries,
     * escaped and with every match in `<mark>`. Without a match, the text's opening.
     *
     * @param  list<string>  $words  Folded words.
     */
    public function excerpt(array $words, int $length = 160): HtmlString
    {
        $total = count($this->characters);
        $firstMatch = $this->firstMatch($words) ?? 0;
        $start = max(0, $firstMatch - intdiv($length, 4));
        $end = min($total, $start + $length);
        $start = max(0, $end - $length);

        if ($start > 0) {
            $start = $this->wordStartFrom($start, $firstMatch);
        }

        if ($end < $total) {
            $end = $this->wordEndBefore($end, $start);
        }

        return new HtmlString(
            ($start > 0 ? '… ' : '').$this->marked($words, $start, $end).($end < $total ? ' …' : '')
        );
    }

    /**
     * @param  list<string>  $words
     * @return null|int The character index of the earliest match.
     */
    private function firstMatch(array $words): ?int
    {
        $first = null;

        foreach ($words as $word) {
            $position = strpos($this->folded, $word);

            if ($position !== false && ($first === null || $this->characterIndexes[$position] < $first)) {
                $first = $this->characterIndexes[$position];
            }
        }

        return $first;
    }

    /**
     * @param  list<string>  $words
     */
    private function marked(array $words, int $start, int $end): string
    {
        $isMarked = [];

        foreach ($words as $word) {
            $offset = 0;

            while (($position = strpos($this->folded, $word, $offset)) !== false) {
                $last = $this->characterIndexes[$position + strlen($word) - 1];

                for ($index = $this->characterIndexes[$position]; $index <= $last; $index++) {
                    $isMarked[$index] = true;
                }

                $offset = $position + 1;
            }
        }

        $html = '';
        $isInMark = false;

        for ($index = $start; $index < $end; $index++) {
            $isMarkedHere = isset($isMarked[$index]);

            if ($isMarkedHere !== $isInMark) {
                $html .= $isMarkedHere ? '<mark>' : '</mark>';
                $isInMark = $isMarkedHere;
            }

            $html .= e($this->characters[$index]);
        }

        return $html.($isInMark ? '</mark>' : '');
    }

    /**
     * The first character after a space from the given index up to the first match, so the excerpt doesn't open mid-word.
     */
    private function wordStartFrom(int $index, int $firstMatch): int
    {
        for ($position = $index; $position <= $firstMatch; $position++) {
            if ($this->characters[$position - 1] === ' ') {
                return $position;
            }
        }

        return $index;
    }

    /**
     * The index of the last space before the given index, so the excerpt doesn't close mid-word.
     */
    private function wordEndBefore(int $index, int $start): int
    {
        for ($position = $index; $position > $start; $position--) {
            if ($this->characters[$position] === ' ') {
                return $position;
            }
        }

        return $index;
    }
}
