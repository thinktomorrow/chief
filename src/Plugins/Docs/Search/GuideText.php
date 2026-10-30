<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Search;

use Thinktomorrow\Chief\Plugins\Docs\GuideHeading;

/**
 * A guide's body as plain text, as search reads it.
 */
final class GuideText
{
    /**
     * @param  string  $body  The body's text on one line, headings included.
     * @param  list<GuideHeading>  $headings  The `##` and `###` headings in document order.
     */
    public function __construct(
        public readonly string $body,
        public readonly array $headings,
    ) {}
}
