<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\Publishable;

use Thinktomorrow\Chief\ManagedModels\States\PageState\PageState;

trait Publishable
{
    public function isPublished(): bool
    {
        return $this->getPageState() === PageState::published;
    }

    public function isDraft(): bool
    {
        return $this->getPageState() === PageState::draft;
    }

    public function scopePublished($query)
    {
        // Here we widen up the results in case of preview mode and ignore the published scope
        if (PreviewMode::fromRequest()->check()) {
            return;
        }

        $query->where($this->getPageStateAttribute(), PageState::published);
    }

    public function scopeDrafted($query)
    {
        $query->where($this->getPageStateAttribute(), PageState::draft);
    }

    public static function getAllPublished()
    {
        return static::published()->get();
    }
}
