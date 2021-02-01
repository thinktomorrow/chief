<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Admin\Widgets;

use Illuminate\Support\Collection;

final class Widgets
{
    private array $widgets;

    private function __construct(array $widgets)
    {
        array_map(function(Widget $widget){}, $widgets);
        $this->widgets = $widgets;
    }

    public static function fromArray(array $widgetClasses): self
    {
        return new static(array_map(function(string $widgetClass){
            return app($widgetClass);
        }, $widgetClasses));
    }

    public function get(): Collection
    {
        return collect($this->widgets);
    }
}
