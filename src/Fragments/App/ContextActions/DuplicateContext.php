<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\App\ContextActions;

use Thinktomorrow\Chief\Fragments\ContextOwner;
use Thinktomorrow\Chief\Shared\ModelReferences\ReferableModel;

class DuplicateContext
{
    private string $sourceContextId;

    private ContextOwner&ReferableModel $targetModel;

    public function __construct(string $sourceContextId, ReferableModel&ContextOwner $targetModel)
    {
        $this->sourceContextId = $sourceContextId;
        $this->targetModel = $targetModel;
    }

    public function getSourceContextId(): string
    {
        return $this->sourceContextId;
    }

    public function getTargetModel(): ReferableModel&ContextOwner
    {
        return $this->targetModel;
    }
}
