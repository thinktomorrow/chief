<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\Archivable;

use Thinktomorrow\Chief\ManagedModels\States\PageState;

trait Archivable
{
    protected static function bootArchivable()
    {
        static::addGlobalScope(new ArchiveScope());
    }

    public function isArchived(): bool
    {
        return $this->stateOf(PageState::KEY) === PageState::ARCHIVED;
    }

    public function scopeArchived($query)
    {
        $query->withoutGlobalScope(ArchiveScope::class)->where(PageState::KEY, PageState::ARCHIVED);
    }

    public function scopeUnarchived($query)
    {
        $query->withoutGlobalScope(ArchiveScope::class)->where(PageState::KEY, '<>', PageState::ARCHIVED);
    }

    public function scopeWithArchived($query)
    {
        $query->withoutGlobalScope(ArchiveScope::class);
    }

    public function archive()
    {
        PageState::make($this)->apply('archive');
        $this->save();
    }

    public function unarchive()
    {
        PageState::make($this)->apply('unarchive');
        $this->save();
    }
}
