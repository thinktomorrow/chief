<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\ManagedModels\Actions\Duplicate;

use Thinktomorrow\Chief\Fragments\Database\ContextModel;
use Thinktomorrow\Chief\Fragments\Database\FragmentModel;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;

class DuplicateContext
{
    private DuplicateFragment $duplicateFragment;

    public function __construct(DuplicateFragment $duplicateFragment)
    {
        $this->duplicateFragment = $duplicateFragment;
    }

    public function handle($sourceModel, $targetModel): void
    {
        if (! $sourceModel instanceof FragmentsOwner || ! $context = ContextModel::ownedBy($sourceModel)) {
            return;
        }

        /** @var FragmentModel $fragment */
        foreach ($context->fragments as $index => $fragment) {
            $this->duplicateFragment->handle($targetModel, $fragment, $index);
        }
    }
}
