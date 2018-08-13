<?php
namespace Thinktomorrow\Chief\App\Http\Controllers\Back\Translations;

use Illuminate\Http\Request;
use Thinktomorrow\Squanto\Domain\Line;
use Thinktomorrow\Squanto\Domain\Page;
use Thinktomorrow\Squanto\Services\CachedTranslationFile;
use Thinktomorrow\Squanto\Manager\Http\Controllers\TranslationController as SquantoController;

class TranslationController extends SquantoController
{
    public function index()
    {
        $this->authorize('view-squanto');

        $pages = Page::sequence()->get();
        $pages->each(function ($page) {
            $page->groupedlines = $this->groupLinesByKey($page);
        });
        return view('squanto::index', compact('pages'));
    }

    public function update(Request $request, $page_id)
    {
        $this->authorize('update-squanto');
        
        return parent::update($request, $page_id);
    }

    public function edit($id)
    {
        $this->authorize('udpate-squanto');
        
        return parent::edit($id);
    }
}
