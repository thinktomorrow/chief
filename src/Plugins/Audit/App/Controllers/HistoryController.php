<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\App\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Plugins\Audit\VisibleHistory;

final class HistoryController extends Controller
{
    public function index(Request $request, VisibleHistory $history): View
    {
        $filters = $request->validate([
            'search' => 'sometimes|nullable|string|max:190',
            'type' => ['sometimes', 'nullable', function (string $attribute, mixed $value, \Closure $fail): void {
                if (! is_string($value) && ! is_array($value) || is_string($value) && mb_strlen($value) > 190 || is_array($value) && count($value) > 20) {
                    $fail('Ongeldige typesleutel.');
                }
            }],
            'type.*' => 'string|max:190',
            'category' => 'sometimes|nullable|string|max:100',
            'outcome' => 'sometimes|nullable|string|max:100',
            'actor' => 'sometimes|nullable|string|max:190',
            'actor_type' => 'sometimes|nullable|string|max:20',
            'model_type' => 'sometimes|nullable|string|max:190',
            'model_id' => 'sometimes|nullable|string|max:190',
            'from' => 'sometimes|nullable|date_format:Y-m-d',
            'to' => 'sometimes|nullable|date_format:Y-m-d',
            'per_page' => 'sometimes|integer|min:1|max:100',
            'page' => 'sometimes|integer|min:1',
            'show_all' => 'sometimes|boolean',
        ]);

        return view('chief-audit::index', [
            'events' => $history->paginate($filters, (int) ($filters['per_page'] ?? 50), (int) ($filters['page'] ?? 1)),
            'options' => $history->filterOptions(),
            'timezone' => $history->timezone(),
            'history' => $history,
            'activeFilters' => $history->hasActiveFilters($filters),
        ]);
    }

    public function eventDetails(VisibleHistory $history, string $event): View
    {
        return view('chief-audit::event-details', ['event' => $history->eventDetail($event), 'timezone' => $history->timezone()]);
    }

    public function details(VisibleHistory $history, string $event, string $model): View
    {
        return view('chief-audit::details', ['model' => $history->detail($event, $model)]);
    }
}
