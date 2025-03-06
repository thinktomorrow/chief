<?php

namespace Thinktomorrow\Chief\Fragments\App\Controllers\Nested;

use Thinktomorrow\Chief\Fragments\App\Controllers\DetachOrDeleteFragmentController;

class DeleteNestedFragmentController
{
    private DetachOrDeleteFragmentController $defaultController;

    public function __construct(DetachOrDeleteFragmentController $defaultController)
    {
        $this->defaultController = $defaultController;
    }

    public function delete(string $contextId, string $fragmentId)
    {
        return $this->defaultController->delete($contextId, $fragmentId);
    }
}
