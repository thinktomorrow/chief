<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs;

/**
 * A `##` or `###` heading of a guide, linkable by its anchor.
 */
final class GuideHeading
{
    public function __construct(
        public readonly string $id,
        public readonly string $title,
    ) {}
}
