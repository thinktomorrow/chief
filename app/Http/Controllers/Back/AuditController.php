<?php

namespace Thinktomorrow\Chief\App\Http\Controllers\Back;

use Thinktomorrow\Chief\Admin\Audit\Audit;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;

class AuditController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $this->authorize('view-audit');

        return view('chief::admin.audit.index', [
            'audit' => Audit::getPaginatedAudit(),
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show($id)
    {
        $this->authorize('view-audit');

        $causerSnapshot = Audit::findCauserSnapshot((int) $id);

        abort_if($causerSnapshot === null, 404);

        return view('chief::admin.audit.show', [
            'audit' => Audit::getPaginatedAuditByCauserId((int) $id),
            'causerSnapshot' => $causerSnapshot,
        ]);
    }
}
