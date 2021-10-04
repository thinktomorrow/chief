<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Assistants;

interface HasBookmark
{
    public function getBookmark(): string;
}
