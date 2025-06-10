<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Seo\Events;

class AltUpdated
{
    public function __construct(public readonly string $assetId) {}
}
