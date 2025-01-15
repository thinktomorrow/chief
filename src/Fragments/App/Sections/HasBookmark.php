<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\Sections;

interface HasBookmark
{
    public function getBookmark(): string;
}
