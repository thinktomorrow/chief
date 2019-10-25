<?php

namespace Thinktomorrow\Chief\States\Publishable;

use Thinktomorrow\Chief\States\PageState;

trait Publishable
{
    public function isPublished(): bool
    {
        return $this->state() === PageState::PUBLISHED;
    }

    public function isDraft(): bool
    {
        return $this->state() === PageState::DRAFT;
    }

    public function scopePublished($query)
    {
        // Here we widen up the results in case of preview mode and ignore the published scope
        if (PreviewMode::fromRequest()->check()) {
            return;
        }

        $query->where('current_state', PageState::PUBLISHED);
    }

    public function scopeDrafted($query)
    {
        $query->where('current_state', PageState::DRAFT);
    }

    public static function getAllPublished()
    {
        return self::published()->get();
    }
}
