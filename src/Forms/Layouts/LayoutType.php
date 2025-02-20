<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Layouts;

enum LayoutType: string
{
    case none = 'none';
    case default = 'default';
    case success = 'success';
    case info = 'info';
    case warning = 'warning';
    case error = 'error';

    public function cardClass(): string
    {
        return match ($this) {
            self::none => '',
            self::default => 'bg-grey-50 border border-grey-100',
            self::success => 'bg-green-50 border border-green-100',
            self::info => 'bg-blue-50 border border-blue-100',
            self::warning => 'bg-orange-50 border border-orange-100',
            self::error => 'bg-red-50 border border-red-100',
        };
    }

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
