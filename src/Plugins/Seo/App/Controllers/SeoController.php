<?php

namespace Thinktomorrow\Chief\Plugins\Seo\App\Controllers;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Plugins\Seo\UI\Livewire\GetAssetsTable;

class SeoController extends Controller
{
    public function __construct() {}

    public function index()
    {
        $this->authorize('update-page');

        return view('chief-seo::index', [

        ]);
    }

    public function assetsIndex()
    {
        $this->authorize('update-page');

        return view('chief-seo::assets.index', [
            'table' => app(GetAssetsTable::class)->getTable(),
        ]);
    }
}
