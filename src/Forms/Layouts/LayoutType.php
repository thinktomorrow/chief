<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Layouts;

enum LayoutType: string
{
    case none = 'none';
    case default = 'grey';
    case success = 'success';
    case info = 'info';
    case warning = 'warning';
    case error = 'error';

    public function class(): string
    {
        return match ($this) {
            self::none => '',
            self::default => 'bg-grey-500 bg-opacity-5',
            self::success => 'bg-green-500 bg-opacity-5',
            self::info => 'bg-blue-500 bg-opacity-5',
            self::warning => 'bg-orange-500 bg-opacity-5',
            self::error => 'bg-red-500 bg-opacity-5',
        };
    }

    public function titleClass(): string
    {
        return match ($this) {
            self::none => '',
            self::default => 'text-grey-500',
            self::success => 'text-green-500',
            self::info => 'text-blue-500',
            self::warning => 'text-orange-500',
            self::error => 'text-red-500',
        };
    }
}
