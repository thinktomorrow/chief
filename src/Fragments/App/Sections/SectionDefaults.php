<?php

namespace Thinktomorrow\Chief\Fragments\App\Sections;

trait SectionDefaults
{
    public function allowedFragments(): array
    {
        return [];
    }

    public function getBookmark(): string
    {
        return 'bookmark-'.$this->fragmentModel()->id;
    }
}
