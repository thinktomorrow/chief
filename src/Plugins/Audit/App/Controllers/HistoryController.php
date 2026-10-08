<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\App\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Plugins\Audit\App\HistoryIndexRequest;
use Thinktomorrow\Chief\Plugins\Audit\Reading\HistoryFilters;
use Thinktomorrow\Chief\Plugins\Audit\Reading\HistoryTimeline;
use Thinktomorrow\Chief\Plugins\Audit\Reading\VisibleHistory;

final class HistoryController extends Controller
{
    public function index(HistoryIndexRequest $request, HistoryTimeline $timeline, VisibleHistory $history): View
    {
        $values = $request->validated();
        $filters = new HistoryFilters($values);

        return view('chief-audit::index', [
            'events' => $timeline->paginate($filters, (int) ($values['per_page'] ?? 50), (int) ($values['page'] ?? 1), (bool) ($values['show_all'] ?? false)),
            'options' => $timeline->filterOptions(),
            'timezone' => $history->timezone(),
            'history' => $history,
            'activeFilters' => $filters->isActive(),
        ]);
    }

    public function eventDetails(VisibleHistory $history, string $event): View
    {
        $detail = $history->eventDetail($event);

        return view('chief-audit::event-details', ['event' => $detail, 'history' => $history, 'timezone' => $history->timezone(), 'missingMailPreview' => $history->missingMailPreview($detail)]);
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
