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

    public function class(): string
    {
        return match ($this) {
            self::none => '',
            self::default => 'bg-primary-50 border border-primary-100',
            self::success => 'bg-green-50 border border-green-100',
            self::info => 'bg-blue-50 border border-blue-100',
            self::warning => 'bg-orange-50 border border-orange-100',
            self::error => 'bg-red-50 border border-red-100',
        };
    }
}
