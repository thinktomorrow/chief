<?php

namespace Thinktomorrow\Chief\Fragments\Bookmark;

use Thinktomorrow\Chief\Forms\Fields\Bookmark;

trait BookmarkDefaults
{
    public function getBookmark(): string
    {
        return $this->bookmark_label ?: 'bookmark-' . $this->fragmentModel()->id;
    }

    private function bookmarkField(): Bookmark
    {
        return Bookmark::make('bookmark_label')
            ->default($this->getBookmark());
    }
}
