<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Search;

use Illuminate\Support\HtmlString;

/**
 * A guide that matches a search, as the search dialog lists it.
 */
final class GuideSearchResult
{
    /**
     * @param  null|string  $section  The title of the section that holds the guide.
     * @param  HtmlString  $snippet  Escaped text with the matched words in `<mark>`.
     * @param  string  $url  The guide's URL, with the anchor of its best-matching heading.
     */
    public function __construct(
        public readonly ?string $section,
        public readonly string $title,
        public readonly HtmlString $snippet,
        public readonly string $url,
    ) {}
}
