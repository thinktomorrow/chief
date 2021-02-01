<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;

use Thinktomorrow\Chief\Admin\Users\User;
use Thinktomorrow\Chief\Admin\Audit\Audit;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class AuditController extends Controller
{
    public function index()
    {
        $this->authorize('view-audit');

        $activity = Audit::getActivity();

        return view('chief::back.audit.index', compact('activity'));
    }

    public function show($id)
    {
        $this->authorize('view-audit');

        $causer     = User::findOrFail($id);
        $activity   = Audit::getActivityBy($causer);

        return view('chief::back.audit.show', compact('activity', 'causer'));
    }
}
