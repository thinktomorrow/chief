<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;

use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\Common\Audit\Audit;
use Thinktomorrow\Chief\Users\User;

class AuditController extends Controller
{
    public function index()
    {
        $activity = Audit::getActivity();

        return view('chief::back.audit.index', compact('activity'));
    }

    public function show($id)
    {
        $causer     = User::findOrFail($id);
        $activity   = Audit::getActivityBy($causer);

        return view('chief::back.audit.show', compact('activity', 'causer'));
    }
}
