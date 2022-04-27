<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\Archivable;

use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;

trait Archivable
{
    protected static function bootArchivable()
    {
        static::addGlobalScope(new ArchiveScope());
    }

    public function isArchived(): bool
    {
        return $this->getPageState() === PageState::archived;
    }

    public function scopeArchived($query)
    {
        $query->withoutGlobalScope(ArchiveScope::class)->where($this->getPageStateAttribute(), PageState::archived);
    }

    public function scopeUnarchived($query)
    {
        $query->withoutGlobalScope(ArchiveScope::class)->where($this->getPageStateAttribute(), '<>', PageState::archived);
    }

    public function scopeWithArchived($query)
    {
        $query->withoutGlobalScope(ArchiveScope::class);
    }
}
