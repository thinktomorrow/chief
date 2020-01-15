<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\States\Publishable;

use Thinktomorrow\Chief\States\PageState;

trait Publishable
{
    public function isPublished(): bool
    {
        return $this->stateOf(PageState::KEY) === PageState::PUBLISHED;
    }

    public function isDraft(): bool
    {
        return $this->stateOf(PageState::KEY) === PageState::DRAFT;
    }

    public function scopePublished($query)
    {
        // Here we widen up the results in case of preview mode and ignore the published scope
        if (PreviewMode::fromRequest()->check()) {
            return;
        }

        $query->where(PageState::KEY, PageState::PUBLISHED);
    }

    public function scopeDrafted($query)
    {
        $query->where(PageState::KEY, PageState::DRAFT);
    }

    public static function getAllPublished()
    {
        return static::published()->get();
    }
}
