<?php
namespace Thinktomorrow\Chief\App\Http\Controllers\Back\Translations;

use Illuminate\Http\Request;
use Thinktomorrow\Squanto\Domain\Line;
use Thinktomorrow\Squanto\Domain\Page;
use Thinktomorrow\Squanto\Services\CachedTranslationFile;
use Thinktomorrow\Squanto\Manager\Http\Controllers\LineController as SquantoLineController;

class LineController extends SquantoLineController
{
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
