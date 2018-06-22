<?php

namespace Thinktomorrow\Chief\Menu;

interface ActsAsMenuItem
{
    public function menuUrl(): string;

    public function menuLabel(): string;
}
