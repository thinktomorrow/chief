<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Callouts;

use League\CommonMark\Node\Block\AbstractBlock;

final class Callout extends AbstractBlock
{
    public function __construct(public readonly CalloutType $type)
    {
        parent::__construct();
    }
}
