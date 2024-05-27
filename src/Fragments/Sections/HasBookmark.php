<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Sections;

interface HasBookmark
{
    public function getBookmark(): string;
}
