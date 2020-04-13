<?php


namespace Thinktomorrow\Chief\Templates;


interface TemplateApplicator
{
    public function handle($sourceModel, $targetModel): void;

    public function shouldApply($sourceModel, $targetModel): bool;
}
