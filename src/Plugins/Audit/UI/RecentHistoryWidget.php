<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\UI;

use Illuminate\Support\Facades\Gate;
use Thinktomorrow\Chief\Admin\Widgets\Widget;
use Thinktomorrow\Chief\Plugins\Audit\Reading\RecentHistory;
use Thinktomorrow\Chief\Plugins\Audit\Reading\VisibleHistory;

final class RecentHistoryWidget implements Widget
{
    public function renderAdminWidget($loop, $widgets): string
    {
        if (! Gate::allows('view-full-audit') && ! Gate::allows('view-related-audit')) {
            return '';
        }

        $filters = array_intersect_key(config('chief.audit.widget.filters', []), array_flip(['type', 'category', 'actor', 'model_type', 'model_id', 'from', 'to']));
        $history = app(VisibleHistory::class);

        return view('chief-audit::widget', [
            'events' => app(RecentHistory::class)->select($filters, (int) config('chief.audit.widget.limit', 5)),
            'history' => $history,
            'filters' => $filters,
            'timezone' => $history->timezone(),
        ])->render();
    }
}
