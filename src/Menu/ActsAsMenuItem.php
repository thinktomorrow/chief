<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Menu;

interface ActsAsMenuItem
{
    public function url(): string;

    public function menuLabel(): string;
}
