<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\Archivable;

use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;

trait Archivable
{
    protected static function getArchiveColumn(): string
    {
        return PageState::KEY;
    }

    protected static function getArchivedValue(): string
    {
        return PageState::archived->value;
    }

    protected static function bootArchivable()
    {
        static::addGlobalScope(new ArchiveScope(static::getArchiveColumn(), static::getArchivedValue()));
    }

    public function isArchived(): bool
    {
        return $this->getState(static::getArchiveColumn())->value === static::getArchivedValue();
    }

    public function scopeArchived($query)
    {
        $query->withoutGlobalScope(ArchiveScope::class)->where(static::getArchiveColumn(), static::getArchivedValue());
    }

    public function scopeUnarchived($query)
    {
        $query->withoutGlobalScope(ArchiveScope::class)->where(static::getArchiveColumn(), '<>', static::getArchivedValue());
    }

    public function scopeWithArchived($query)
    {
        $query->withoutGlobalScope(ArchiveScope::class);
    }
}
