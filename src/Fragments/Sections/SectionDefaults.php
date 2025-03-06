<?php

namespace Thinktomorrow\Chief\Fragments\Sections;

trait SectionDefaults
{
    public function allowedFragments(): array
    {
        return [];
    }

    public function getBookmark(): string
    {
        return 'bookmark-'.$this->getFragmentModel()->id;
    }
}
