<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Reading;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Plugins\Audit\Persistence\AuditEvent;
use Thinktomorrow\Chief\Plugins\Audit\Persistence\AuditEventModel;
use Thinktomorrow\Chief\Plugins\Audit\Persistence\AuditRichData;
use Thinktomorrow\Chief\Plugins\Audit\UI\AuditAppearance;

/** Existing entry point for reading the current viewer's audit history. */
final class VisibleHistory
{
    public function __construct(
        private HistoryRead $read,
        private HistoryTimeline $timeline,
        private HistoryEventDetails $details,
    ) {}

    /** @param array<string, mixed> $filters */
    public function paginate(array $filters = [], int $perPage = 50, int $page = 1): LengthAwarePaginator
    {
        return $this->timeline->paginate(new HistoryFilters($filters), $perPage, $page, (bool) ($filters['show_all'] ?? false));
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return Collection<int, AuditEvent>
     */
    public function select(array $filters = []): Collection
    {
        return $this->read->select(new HistoryFilters($filters));
    }

    /**
     * @param  array<string, mixed>  $filters
     * @return Collection<int, AuditEvent>
     */
    public function recent(array $filters, int $limit, bool $includeSecondary): Collection
    {
        return $this->timeline->recent(new HistoryFilters($filters), $limit, $includeSecondary);
    }

    /** @return array{events: Collection<int, AuditEvent>, hasMore: bool} */
    public function modelHistory(string $modelType, string $modelId, bool $expanded): array
    {
        return $this->timeline->modelHistory($modelType, $modelId, $expanded);
    }

    /** @return array<string, list<string>> */
    public function filterOptions(): array
    {
        return $this->timeline->filterOptions();
    }

    public function presentation(AuditEvent $event): AuditAppearance
    {
        if ($event->type === 'legacy.spatie') {
            $appearance = AuditAppearance::forType($event->type);
            $action = $event->context['legacy']['event'] ?? null;
            $action = is_string($action) ? trim($action) : '';
            $label = match ($action) {
                'published' => 'Gepubliceerd',
                'created' => 'Aangemaakt',
                'updated' => 'Bijgewerkt',
                'deleted' => 'Verwijderd',
                'unpublished' => 'Offline gehaald',
                default => $action !== '' ? ucfirst(str_replace(['_', '.', '-'], ' ', $action)) : ($event->summary ?: 'Historische activiteit'),
            };

            return new AuditAppearance($label, $appearance->icon, $appearance->color, $appearance->priority);
        }

        return AuditAppearance::forType($event->type);
    }

    public function typeLabel(string $type): string
    {
        return $type === 'legacy.spatie' ? 'Historische activiteit' : AuditAppearance::forType($type)->label;
    }

    public function categoryLabel(string $category): string
    {
        if ($category === 'legacy') {
            return 'Historische activiteit';
        }

        return config('chief-audit.categories', [])[$category] ?? $category;
    }

    public function priority(AuditEvent $event): string
    {
        return $this->presentation($event)->priority;
    }

    /** @param array<string, mixed> $filters */
    public function hasActiveFilters(array $filters): bool
    {
        return (new HistoryFilters($filters))->isActive();
    }

    public function timezone(): string
    {
        return $this->read->timezone();
    }

    public function eventDetail(string $eventId): AuditEvent
    {
        return $this->details->eventDetail($eventId);
    }

    public function missingMailPreview(AuditEvent $event): bool
    {
        return $this->details->missingMailPreview($event);
    }

    /** @return Collection<int, AuditRichData> */
    public function richData(string $eventId): Collection
    {
        return $this->details->richData($eventId);
    }

    public function richPiece(string $eventId, string $pieceId, string $type): AuditRichData
    {
        return $this->details->richPiece($eventId, $pieceId, $type);
    }

    public function detail(string $eventId, string $linkId): AuditEventModel
    {
        return $this->details->detail($eventId, $linkId);
    }
}
