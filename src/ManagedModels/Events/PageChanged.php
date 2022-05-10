<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Events;

use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

/**
 * Generic event that indicates the page record,
 * fragments or any of the related data has changed.
 */
class PageChanged
{
    public function __construct(public readonly ModelReference $modelReference)
    {
    }
}
