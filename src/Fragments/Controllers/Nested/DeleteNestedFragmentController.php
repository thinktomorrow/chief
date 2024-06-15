<?php

namespace Thinktomorrow\Chief\Fragments\Controllers\Nested;

use Thinktomorrow\Chief\Fragments\Controllers\DetachOrDeleteFragmentController;

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
