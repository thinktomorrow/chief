<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\App\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Gate;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Plugins\Audit\AuditEvent;
use Thinktomorrow\Chief\Plugins\Audit\AuditEventModel;

final class HistoryController extends Controller
{
    public function index(): View
    {
        $this->authorize('view-audit');

        $events = Gate::allows('view-full-audit')
            ? AuditEvent::query()->with('models')->orderByDesc('occurred_at')->orderByDesc('id')->paginate(50)
            : new LengthAwarePaginator([], 0, 50);

        return view('chief-audit::index', ['events' => $events]);
    }

    public function details(string $event, string $model): View
    {
        $this->authorize('view-audit');
        $this->authorize('view-full-audit');

        $event = AuditEvent::query()->findOrFail($event);
        $model = AuditEventModel::query()->where('event_id', $event->getKey())->findOrFail($model);
        abort_unless($model->changes, 404);

        return view('chief-audit::details', ['event' => $event, 'model' => $model]);
    }
}
