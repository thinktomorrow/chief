<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs;

/**
 * A guide's body rendered to HTML, with the `##` headings it links to.
 */
final class RenderedGuide
{
    /**
     * @param  list<GuideHeading>  $headings  In document order.
     */
    public function __construct(
        public readonly string $content,
        public readonly array $headings,
    ) {}
}
