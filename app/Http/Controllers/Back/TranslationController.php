<?php
namespace App\Http\Controllers\Back;
use Illuminate\Http\Request;
use Thinktomorrow\Squanto\Domain\Line;
use Thinktomorrow\Squanto\Domain\Page;
use Thinktomorrow\Squanto\Services\CachedTranslationFile;
use Thinktomorrow\Squanto\Manager\Http\Controllers\TranslationController as SquantoController;
class TranslationController extends SquantoController
{
    public function index()
    {
        $pages = Page::sequence()->get();
        $pages->each(function($page){
            $page->groupedlines = $this->groupLinesByKey($page);
        });
        return view('squanto::index', compact('pages'));
    }
}