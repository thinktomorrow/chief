<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs;

/**
 * A node in the guide tree: a guide, or a section folder without a page.
 */
final class GuideNode
{
    /**
     * @param  null|string  $markdown  The guide's Markdown body without front matter. Null for a section without a page.
     */
    public function __construct(
        public readonly string $id,
        public readonly string $title,
        public readonly ?string $description = null,
        public readonly ?int $order = null,
        public readonly ?string $markdown = null,
    ) {}

    public function hasPage(): bool
    {
        return $this->markdown !== null;
    }
}
