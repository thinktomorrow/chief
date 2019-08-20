<?php

namespace Thinktomorrow\Chief\States\Publishable;

use Illuminate\Support\Facades\Session;

trait CheckPreviewTrait
{
    public function isPreviewAllowed()
    {
        if (PreviewMode::fromRequest()->check()) {
            Session::now('note.default', 'U bekijkt een preview.');
            return true;
        } else {
            return false;
        }
    }
}
