<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;

use Thinktomorrow\Chief\Admin\Audit\Audit;
use Thinktomorrow\Chief\Admin\Users\User;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class AuditController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $this->authorize('view-audit');

        $activity = Audit::getActivity();

        return view('chief::admin.audit.index', compact('activity'));
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $this->authorize('view-audit');

        $causer = User::findOrFail($id);
        $activity = Audit::getActivityBy($causer);

        return view('chief::admin.audit.show', compact('activity', 'causer'));
    }
}
