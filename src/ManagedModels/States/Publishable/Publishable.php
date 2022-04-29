<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\Publishable;

use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;

trait Publishable
{
    protected function getPublishablePageStateAttribute(): string
    {
        return PageState::KEY;
    }

    public function isPublished(): bool
    {
        return $this->inOnlineState();
    }

    public function isDraft(): bool
    {
        return $this->getState(\Thinktomorrow\Chief\ManagedModels\States\PageState\PageState::KEY) === PageState::draft;
    }

    public function scopePublished($query)
    {
        // Here we widen up the results in case of preview mode and ignore the published scope
        if (PreviewMode::fromRequest()->check()) {
            return;
        }

        $query->where($this->getPublishablePageStateAttribute(), PageState::published);
    }

    public function scopeDrafted($query)
    {
        $query->where($this->getPublishablePageStateAttribute(), PageState::draft);
    }

    public static function getAllPublished()
    {
        return static::published()->get();
    }
}
