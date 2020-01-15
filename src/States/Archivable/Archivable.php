<?php declare(strict_types=1);

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
        return $this->stateOf(PageState::KEY) === PageState::ARCHIVED;
    }

    public function scopeArchived($query)
    {
        $query->withoutGlobalScope(ArchiveScope::class)->where(PageState::KEY, PageState::ARCHIVED);
    }

    public function scopeUnarchived($query)
    {
        $query->withoutGlobalScope(ArchiveScope::class)->where(PageState::KEY,'<>', PageState::ARCHIVED);
    }

    public function scopeWithArchived($query)
    {
        $query->withoutGlobalScope(ArchiveScope::class);
    }
}
