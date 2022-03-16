<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Layouts;

enum Layout: string
{
    case card = 'card';
    case blank = 'blank';

    public function class(): string
    {
        return match ($this) {
            self::card => 'window',
            self::blank => '',
        };
    }
}
