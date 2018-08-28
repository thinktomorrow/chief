<?php

namespace Thinktomorrow\Chief\Common\Relations;

interface PresentForParent
{
    public function presentForParent(ActsAsParent $parent): string;
}
