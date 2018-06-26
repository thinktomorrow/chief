<?php

namespace Thinktomorrow\Chief\Common\Traits;

use Illuminate\Support\Facades\Session;
use Thinktomorrow\Chief\Common\Publish\PreviewMode;

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
