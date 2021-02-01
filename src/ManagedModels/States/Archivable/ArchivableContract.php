<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\Archivable;

interface ArchivableContract
{
    public function isArchived(): bool;
}
