<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\App\Controllers;

use Illuminate\Contracts\View\View;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Plugins\Audit\AuditEvent;

final class HistoryController extends Controller
{
    public function index(): View
    {
        $this->authorize('view-audit');
        $this->authorize('view-full-audit');

        return view('chief-audit::index', [
            'events' => AuditEvent::query()->orderByDesc('occurred_at')->orderByDesc('id')->paginate(50),
        ]);
    }
}
