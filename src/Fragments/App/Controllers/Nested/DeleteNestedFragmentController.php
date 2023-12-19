<?php

namespace Thinktomorrow\Chief\Fragments\App\Controllers\Nested;

use Thinktomorrow\Chief\Fragments\App\Controllers\DeleteFragmentController;

class DeleteNestedFragmentController
{
    private DeleteFragmentController $defaultController;

    public function __construct(DeleteFragmentController $defaultController)
    {
        $this->defaultController = $defaultController;
    }

    public function delete(string $contextId, string $fragmentId)
    {
        return $this->defaultController->delete($contextId, $fragmentId);
    }
}
