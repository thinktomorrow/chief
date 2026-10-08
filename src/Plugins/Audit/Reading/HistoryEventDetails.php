<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Reading;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Plugins\Audit\Persistence\AuditEvent;
use Thinktomorrow\Chief\Plugins\Audit\Persistence\AuditEventModel;
use Thinktomorrow\Chief\Plugins\Audit\Persistence\AuditRichData;

final class HistoryEventDetails
{
    public function __construct(private HistoryRead $read) {}

    public function eventDetail(string $eventId): AuditEvent
    {
        $event = $this->read->select(HistoryFilters::forEvent($eventId))->first();
        abort_unless($event && ($event->context || ! $event->recorded_at->equalTo($event->occurred_at) || $this->missingMailPreview($event)), 404);

        return $event;
    }

    public function missingMailPreview(AuditEvent $event): bool
    {
        return $this->read->fullAccess() && $event->type === 'chief.mail.invitation.sent' && ! $event->has_rich_data;
    }

    /** @return Collection<int, AuditRichData> */
    public function richData(string $eventId): Collection
    {
        $event = $this->read->select(HistoryFilters::forEvent($eventId))->first();
        abort_unless($event, 404);

        $visibleLinkIds = $event->models->pluck('id')->all();
        $allLinkCount = AuditEventModel::query()->where('event_id', $eventId)->count();
        $wholeEvent = $this->read->fullAccess() || ($allLinkCount > 0 && count($visibleLinkIds) === $allLinkCount);
        abort_if(! $wholeEvent && $visibleLinkIds === [], 404);

        $pieces = AuditRichData::query()->where('event_id', $eventId)
            ->where(function (Builder $query) use ($wholeEvent, $visibleLinkIds): void {
                if ($wholeEvent) {
                    $query->whereNull('model_link_id');
                }
                if ($visibleLinkIds !== []) {
                    $query->orWhereIn('model_link_id', $visibleLinkIds);
                }
            })->orderBy('id')->get();

        abort_if($pieces->isEmpty(), 404);

        return $pieces;
    }

    public function richPiece(string $eventId, string $pieceId, string $type): AuditRichData
    {
        $piece = $this->richData($eventId)->first(fn (AuditRichData $piece) => (string) $piece->getKey() === $pieceId && ($piece->type === $type || $type === 'html' && $piece->type === 'mailpreview') && $piece->status === 'available');
        abort_unless($piece, 404);

        return $piece;
    }

    public function detail(string $eventId, string $linkId): AuditEventModel
    {
        $event = AuditEvent::query()->findOrFail($eventId);
        $link = AuditEventModel::query()->where('event_id', $event->getKey())->findOrFail($linkId);
        abort_unless($link->changes || $link->context, 404);
        abort_unless($this->read->canViewModel($link->model_type, $link->model_id), 404);

        return $link;
    }
}
