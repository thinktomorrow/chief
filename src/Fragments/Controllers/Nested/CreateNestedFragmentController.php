<?php

namespace Thinktomorrow\Chief\Fragments\Controllers\Nested;

use Illuminate\Http\Request;
use Thinktomorrow\Chief\Fragments\Controllers\CreateFragmentController;

class CreateNestedFragmentController
{
    private CreateFragmentController $defaultController;

    public function __construct(CreateFragmentController $defaultController)
    {
        $this->defaultController = $defaultController;
    }

    public function create(string $contextId, string $fragmentKey, Request $request)
    {
        return $this->defaultController->create($contextId, $fragmentKey, $request);
    }

    public function store(string $contextId, string $fragmentKey, Request $request)
    {
        $redirectToRouteIfFragmentsOwner = 'chief::fragments.nested.edit';

        return $this->defaultController->store($contextId, $fragmentKey, $request, $redirectToRouteIfFragmentsOwner);
    }
}
