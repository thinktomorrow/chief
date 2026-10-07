<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\UI;

use Illuminate\Support\Facades\Gate;
use Thinktomorrow\Chief\Admin\Widgets\Widget;
use Thinktomorrow\Chief\Plugins\Audit\RecentHistory;

final class RecentHistoryWidget implements Widget
{
    public function renderAdminWidget($loop, $widgets): string
    {
        if (! Gate::allows('view-full-audit') && ! Gate::allows('view-related-audit')) {
            return '';
        }

        $filters = array_intersect_key(config('chief.audit.widget.filters', []), array_flip(['type', 'category', 'actor', 'model_type', 'model_id', 'from', 'to']));
        $history = app(RecentHistory::class);

        return view('chief-audit::widget', [
            'events' => $history->select($filters, (int) config('chief.audit.widget.limit', 5)),
            'filters' => $filters,
            'timezone' => config('chief.audit.timezone', config('app.timezone', 'UTC')),
        ])->render();
    }
}
