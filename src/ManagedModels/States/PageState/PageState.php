<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\States\PageState;

use Thinktomorrow\Chief\ManagedModels\States\State\State;

enum PageState: string implements State
{
    const KEY = 'current_state';

    case draft = 'draft';
    case archived = 'archived';
    case deleted = 'deleted';
    case published = 'published';

//    public function isOffline(): bool
//    {
//        return in_array($this->statefulContract->stateOf($this->statefulContract->getPageStateAttribute()), [
//            static::DRAFT,
//            static::ARCHIVED,
//            static::DELETED,
//        ]);
//    }
//
//    public function isOnline(): bool
//    {
//        return in_array($this->statefulContract->stateOf($this->statefulContract->getPageStateAttribute()), [
//            static::PUBLISHED,
//        ]);
//    }
    public function getValueAsString(): string
    {
        return $this->value;
    }
}
