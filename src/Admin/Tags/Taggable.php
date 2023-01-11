<?php

namespace Thinktomorrow\Chief\Admin\Tags;

interface Taggable
{
    public function getTaggableType(): string;

    public function getTaggableId(): string;
}
