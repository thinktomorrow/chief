<?php

namespace Thinktomorrow\Chief\Relations;

interface PresentForParent
{
    public function viewKey(): string;

    public function presentForParent(ActsAsParent $parent): string;
}
