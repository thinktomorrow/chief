<?php


namespace Thinktomorrow\Chief\ManagedModels\States;


use Thinktomorrow\Chief\ManagedModels\States\State\StatefulContract;

interface WithPageState extends StatefulContract
{
    public function getPageState(): string;

    public function setPageState($state): void;
}
