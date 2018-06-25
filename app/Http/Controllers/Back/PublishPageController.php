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

        $page = Page::ignoreCollection()->findOrFail($id);

        if( ! $page->isPublished()) {
            $page->publish();
        }

        return redirect()->back();
    }

    public function draft(Request $request, $id)
    {
        $this->authorize('update-page');

        $page = Page::ignoreCollection()->findOrFail($id);

        if( ! $page->isDraft()) {
            $page->draft();
        }

        return redirect()->back();
    }
}
