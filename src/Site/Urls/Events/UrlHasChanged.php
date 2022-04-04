<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Site\Urls\Events;

use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

class UrlHasChanged
{
    public function __construct(public readonly ModelReference $modelReference)
    {
    }
}
