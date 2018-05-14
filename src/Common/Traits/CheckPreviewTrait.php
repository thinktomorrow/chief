<?php

namespace Chief\Common\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

trait CheckPreviewTrait
{
    public function isPreviewAllowed()
    {
        if(request()->has('preview-mode') && Auth::guard('admin')->check())
        {
            Session::now('note.default', 'U bekijkt een preview.');
            return true;
        }else{
            return false;
        }
    }
}