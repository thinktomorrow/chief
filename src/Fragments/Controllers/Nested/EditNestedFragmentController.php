<?php

namespace Thinktomorrow\Chief\Fragments\Controllers\Nested;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fragments\Controllers\EditFragmentController;

class EditNestedFragmentController
{
    private EditFragmentController $defaultController;

    public function __construct(EditFragmentController $defaultController)
    {
        $this->defaultController = $defaultController;
    }

    public function edit(string $contextId, string $fragmentId, Request $request)
    {
        return $this->defaultController->edit($contextId, $fragmentId, $request);
    }

    public function update(string $contextId, string $fragmentId, Request $request)
    {
        return $this->defaultController->update($contextId, $fragmentId, $request);
    }
}
