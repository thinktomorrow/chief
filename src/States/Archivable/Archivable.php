<?php

namespace Thinktomorrow\Chief\States\Archivable;

use Thinktomorrow\Chief\States\PageState;

trait Archivable
{
    protected static function bootArchivable()
    {
        static::addGlobalScope(new ArchiveScope());
    }

    public function isArchived(): bool
    {
        return $this->state() === PageState::ARCHIVED;
    }

    public function scopeArchived($query)
    {
        $query->withoutGlobalScope(ArchiveScope::class)->where('current_state', PageState::ARCHIVED);
    }

    public function scopeUnarchived($query)
    {
        $query->withoutGlobalScope(ArchiveScope::class)->where('current_state','<>', PageState::ARCHIVED);
    }

    public function scopeWithArchived($query)
    {
        $query->withoutGlobalScope(ArchiveScope::class);
    }
}
