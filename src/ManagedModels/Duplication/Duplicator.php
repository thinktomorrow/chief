<?php


namespace Thinktomorrow\Chief\ManagedModels\Duplication;

interface Duplicator
{
    public function handle($sourceModel, $targetModel): void;

    public function shouldApply($sourceModel, $targetModel): bool;
}
