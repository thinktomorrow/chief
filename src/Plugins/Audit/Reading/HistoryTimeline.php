<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Reading;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Plugins\Audit\Persistence\AuditEvent;
use Thinktomorrow\Chief\Plugins\Audit\UI\AuditAppearance;

final class HistoryTimeline
{
    public function __construct(private HistoryRead $read) {}

    public function paginate(HistoryFilters $filters, int $perPage = 50, int $page = 1, bool $showAll = false): LengthAwarePaginator
    {
        $perPage = max(1, min(100, $perPage));
        $page = max(1, $page);
        $selection = new HistoryPageSelection($perPage, $page, $filters->isActive(), $showAll);

        $this->read->each($filters, function (AuditEvent $event) use ($selection): void {
            $selection->accept($event, $this->priority($event) === 'secondary');
        });

        if ($selection->needsSecondaryWindow()) {
            $this->read->each($filters, function (AuditEvent $event) use ($selection): void {
                $selection->acceptSecondaryInWindow($event, $this->priority($event) === 'secondary');
            });
        }

        return new LengthAwarePaginator($selection->items(), $selection->total(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'query' => request()->query(),
        ]);
    }

    /** @return Collection<int, AuditEvent> */
    public function recent(HistoryFilters $filters, int $limit, bool $includeSecondary): Collection
    {
        $results = collect();
        $this->read->each($filters, function (AuditEvent $event) use ($results, $limit, $includeSecondary): bool {
            if ($includeSecondary || $this->priority($event) !== 'secondary') {
                $results->push($event);
            }

            return $results->count() >= $limit;
        });

        return $results;
    }

    /** @return array{events: Collection<int, AuditEvent>, hasMore: bool} */
    public function modelHistory(string $modelType, string $modelId, bool $expanded): array
    {
        $filters = HistoryFilters::forModel($modelType, $modelId);

        if ($expanded) {
            return ['events' => $this->read->select($filters), 'hasMore' => true];
        }

        $events = collect();
        $visibleCount = 0;
        $this->read->each($filters, function (AuditEvent $event) use ($events, &$visibleCount): bool {
            $visibleCount++;

            if ($this->priority($event) !== 'secondary' && $events->count() < 5) {
                $events->push($event);
            }

            return $visibleCount > 5 && $events->count() === 5;
        });

        return ['events' => $events, 'hasMore' => $visibleCount > $events->count()];
    }

    /** @return array<string, list<string>> */
    public function filterOptions(): array
    {
        $options = ['types' => [], 'categories' => [], 'outcomes' => [], 'model_types' => [], 'actor_types' => [], 'actors' => [], 'models' => []];
        $this->read->each(new HistoryFilters, static function (AuditEvent $event) use (&$options): void {
            foreach (['types' => 'type', 'categories' => 'category', 'outcomes' => 'outcome', 'actor_types' => 'actor_type'] as $key => $field) {
                if ($event->$field !== null) {
                    $options[$key][$event->$field] = $event->$field;
                }
            }
            if (isset($event->actor_snapshot['id'])) {
                $options['actors'][$event->actor_snapshot['id']] = $event->actor_snapshot['name'];
            }
            foreach ($event->models as $model) {
                $options['model_types'][$model->model_type] = $model->model_type;
                $options['models'][$model->model_id] = $model->model_snapshot['name'];
            }
        });

        foreach (['types', 'categories', 'outcomes', 'model_types', 'actor_types'] as $key) {
            $options[$key] = array_values($options[$key]);
        }

        return $options;
    }

    private function priority(AuditEvent $event): string
    {
        return AuditAppearance::forType($event->type)->priority;
    }
}
