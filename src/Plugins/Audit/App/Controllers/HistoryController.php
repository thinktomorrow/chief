<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\App\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Plugins\Audit\AuditPresentations;
use Thinktomorrow\Chief\Plugins\Audit\VisibleHistory;

final class HistoryController extends Controller
{
    public function index(Request $request, VisibleHistory $history, AuditPresentations $presentations): View
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
            'filter' => 'sometimes|array',
            'filter.*' => 'nullable|string|max:190',
        ]);

        foreach (array_keys($filters['filter'] ?? []) as $key) {
            if (! array_key_exists($key, $presentations->filters())) {
                throw ValidationException::withMessages(['filter' => 'Unknown audit filter.']);
            }
        }

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

    public function richData(VisibleHistory $history, string $event): View
    {
        $pieces = $history->richData($event);
        foreach ($pieces as $piece) {
            if ($piece->type === 'reference' && $piece->status === 'available' && ! Storage::disk($piece->disk)->exists($piece->path)) {
                $piece->status = 'unavailable';
                $piece->metadata = [];
            }
        }

        return view('chief-audit::rich-data', ['eventId' => $event, 'pieces' => $pieces]);
    }

    public function richHtml(VisibleHistory $history, string $event, string $piece): Response
    {
        $data = $history->richPiece($event, $piece, 'html');

        return response($data->content)->header('Content-Type', 'text/html; charset=UTF-8')
            ->header('Content-Security-Policy', "sandbox; default-src 'none'; style-src 'unsafe-inline'; img-src data:")
            ->header('X-Content-Type-Options', 'nosniff');
    }

    public function richReference(VisibleHistory $history, string $event, string $piece): StreamedResponse
    {
        $data = $history->richPiece($event, $piece, 'reference');
        abort_unless(Storage::disk($data->disk)->exists($data->path), 404);

        return Storage::disk($data->disk)->download($data->path, basename((string) ($data->metadata['name'] ?? 'reference')));
    }
}
