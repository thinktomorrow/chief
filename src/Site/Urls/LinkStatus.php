<?php

namespace Thinktomorrow\Chief\Site\Urls;

enum LinkStatus: string
{
    case none = 'none'; // No status
    case online = 'online';
    case offline = 'offline';

    public static function options(): array
    {
        return [
            self::online->value => 'Online',
            self::offline->value => 'Offline',
        ];
    }
}
