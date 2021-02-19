<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Thinktomorrow\Squanto\Manager\Http\ManagerController;

class TranslationController extends ManagerController
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('view-squanto');

        return parent::index($request);
    }

    public function update(Request $request, $pageSlug)
    {
        $this->authorize('update-squanto');

        return parent::update($request, $pageSlug);
    }

    public function edit($pageSlug)
    {
        $this->authorize('update-squanto');

        return parent::edit($pageSlug);
    }
}
