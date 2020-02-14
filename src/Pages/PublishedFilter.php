<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Pages;

use Closure;
use Thinktomorrow\Chief\States\PageState;
use Thinktomorrow\Chief\Filters\Types\SelectFilter;

class PublishedFilter extends SelectFilter
{
    public $label = 'filter online/offline';

    public $options = ['online', 'offline', 'all'];

    public static function init()
    {
        return static::make('published');
    }

    public function apply($value = null): Closure
    {
        return function ($query) use ($value) {
            if ($value == 'all') {
                return $query;
            }

            return $query->where('current_state', ($value == 'online') ? '=' : '<>', PageState::PUBLISHED);
        };
    }
}
