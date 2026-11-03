<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Media;

use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Node\Block\AbstractBlock;

/**
 * An image or video that stands on a line of its own, in place of the paragraph that held it.
 */
final class Figure extends AbstractBlock
{
    public function __construct(public readonly Image $media)
    {
        parent::__construct();

        $this->appendChild($media);
    }
}
