<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs;

/**
 * A group of guides as the user guide's sidebar lists them: the top-level
 * guides without children, or one top-level section with its own page first.
 */
final class GuideGroup
{
    /**
     * @param  null|GuideNode  $section  The top-level section the group lists. Null for the top-level guides without children.
     * @param  list<GuideNode>  $nodes
     */
    public function __construct(
        public readonly string $title,
        public readonly ?GuideNode $section,
        public readonly array $nodes,
    ) {}
}
