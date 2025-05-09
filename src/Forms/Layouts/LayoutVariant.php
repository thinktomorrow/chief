<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Layouts;

enum LayoutVariant: string
{
    case none = 'none';
    case default = 'default';
    case success = 'success';
    case info = 'info';
    case warning = 'warning';
    case error = 'error';

    public function labelClass(): string
    {
        return match ($this) {
            self::none => '',
            self::default => 'label label-xs label-grey',
            self::success => 'label label-xs label-success',
            self::info => 'label label-xs label-info',
            self::warning => 'label label-xs label-warning',
            self::error => 'label label-xs label-error',
        };
    }
}
