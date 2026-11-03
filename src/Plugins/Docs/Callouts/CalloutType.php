<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Docs\Callouts;

/**
 * The GitHub alert types a guide can use, written as `> [!TIP]`.
 */
enum CalloutType: string
{
    case Tip = 'TIP';
    case Note = 'NOTE';
    case Warning = 'WARNING';

    public function label(): string
    {
        return match ($this) {
            self::Tip => 'Tip',
            self::Note => 'Goed om te weten',
            self::Warning => 'Let op',
        };
    }

    /**
     * The variant of Chief's callout component.
     */
    public function variant(): string
    {
        return match ($this) {
            self::Tip => 'green',
            self::Note => 'blue',
            self::Warning => 'orange',
        };
    }
}
