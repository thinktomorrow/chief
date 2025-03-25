<?php

namespace Thinktomorrow\Chief\Menu;

enum MenuLinkType: string
{
    case internal = 'internal';
    case custom = 'custom';
    case nolink = 'nolink';

    public static function getOptions(): array
    {
        return [
            self::internal->value => 'Pagina link',
            self::custom->value => 'Eigen link',
            self::nolink->value => 'Geen link',
        ];
    }
}
