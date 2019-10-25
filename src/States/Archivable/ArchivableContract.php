<?php

namespace Thinktomorrow\Chief\States\Archivable;

interface ArchivableContract
{
    public function isArchived(): bool;
}
