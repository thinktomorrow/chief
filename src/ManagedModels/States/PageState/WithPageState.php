<?php


namespace Thinktomorrow\Chief\ManagedModels\States\PageState;

use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;

interface WithPageState extends StatefulContract
{
    public function getPageState(): PageState;

    public function setPageState(PageState $state): void;

    public function getPageStateAttribute(): string;
}
