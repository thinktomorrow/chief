<?php
namespace Thinktomorrow\Chief\App\Http\Controllers\Back\Translations;

use Illuminate\Http\Request;
use Thinktomorrow\Squanto\Manager\Http\Controllers\LineController as SquantoLineController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LineController extends SquantoLineController
{
    use AuthorizesRequests;

    public function index()
    {
        $this->authorize('view-squanto');

        return parent::index();
    }

    public function create($page_id = null)
    {
        $this->authorize('create-squanto');

        return parent::create($page_id);
    }

    public function store(Request $request)
    {
        $this->authorize('create-squanto');

        return parent::store($request);
    }

    public function edit($id)
    {
        $this->authorize('update-squanto');
        
        return parent::edit($id);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('update-squanto');

        return parent::update($request, $id);
    }

    public function destroy($id)
    {
        $this->authorize('delete-squanto');

        return parent::destroy($id);
    }
}
