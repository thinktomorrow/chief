<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Forms\Layouts;

enum LayoutType: string
{
    case none = 'none';
    case grey = 'grey';
    case success = 'success';
    case info = 'info';
    case warning = 'warning';
    case error = 'error';

    public function class(): string
    {
        return match ($this) {
            self::none => '',
            self::grey => 'bg-grey-500 bg-opacity-5',
            self::success => 'bg-green-500 bg-opacity-5',
            self::info => 'bg-blue-500 bg-opacity-5',
            self::warning => 'bg-orange-500 bg-opacity-5',
            self::error => 'bg-red-500 bg-opacity-5',
        };
    }
}
