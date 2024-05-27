<?php

namespace Thinktomorrow\Chief\Fragments\Sections;

trait BookmarkDefaults
{
    public function getBookmark(): string
    {
        return 'bookmark-' . $this->fragmentModel()->id;
    }
}
