<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\Publishable;

use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;

/**
 * @deprecated use the UsesPageState or UsesSimpleState traits instead
 */
trait Publishable
{
    protected function getPublishablePageStateAttribute(): string
    {
        return PageState::KEY;
    }

    /**
     * @deprecated use inOnlineState() instead
     */
    public function isPublished(): bool
    {
        return $this->inOnlineState();
    }

    public function isDraft(): bool
    {
        return $this->getState(\Thinktomorrow\Chief\ManagedModels\States\PageState\PageState::KEY) === PageState::draft;
    }

    /**
     * @deprecated use UsesPageState::scopeOnline(Builder $query) instead
     */
    public function scopePublished($query)
    {
        $this->scopeOnline($query);
    }

    /**
     * @deprecated use $query->where(PageState::KEY, PageState::draft->value) instead
     */
    public function scopeDrafted($query)
    {
        $query->where($this->getPublishablePageStateAttribute(), PageState::draft);
    }

    /**
     * @deprecated use $query->online()->get() instead
     */
    public static function getAllPublished()
    {
        return static::published()->get();
    }
}
