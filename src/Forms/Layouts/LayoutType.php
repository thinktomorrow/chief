<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Layouts;

enum LayoutType: string
{
    case none = 'none';
    case success = 'success';
    case info = 'info';
    case warning = 'warning';
    case error = 'error';

    public function class(): string
    {
        return match ($this) {
            self::none => '',
            self::success => 'bg-green-50 bg-gradient-to-br from-green-50 to-green-100',
            self::info => 'bg-blue-50 bg-gradient-to-br from-blue-50 to-blue-100',
            self::warning => 'bg-orange-50 bg-gradient-to-br from-orange-50 to-orange-100',
            self::error => 'bg-red-50 bg-gradient-to-br from-red-50 to-red-100',
        };
    }
}
