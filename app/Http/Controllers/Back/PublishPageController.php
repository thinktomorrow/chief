<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Pages\Page;
use Illuminate\Http\Request;

class PublishPageController extends Controller
{
    public function publish(Request $request, $id)
    {
        $this->authorize('update-page');

        $page = Page::findOrFail($id);

        if (! $page->isPublished()) {
            $page->publish();
        }

        return redirect()->back()->with('messages.success', $page->title.' is gepubliceerd. <a href="'.$page->menuUrl().'" target="_blank">Bekijk de pagina online</a>.');
    }

    public function draft(Request $request, $id)
    {
        $this->authorize('update-page');

        $page = Page::withArchived()->findOrFail($id);

        if (!$page->isDraft()) {
            $page->draft();
        }

        return redirect()->back();
    }
}
