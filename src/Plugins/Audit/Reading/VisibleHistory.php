<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Reading;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Thinktomorrow\Chief\Admin\Authorization\ChiefResourcePermissions;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Plugins\Audit\Persistence\AuditEvent;
use Thinktomorrow\Chief\Plugins\Audit\Persistence\AuditEventModel;
use Thinktomorrow\Chief\Plugins\Audit\Persistence\AuditRichData;
use Thinktomorrow\Chief\Plugins\Audit\Presentation\AuditPresentations;
use Thinktomorrow\Chief\Plugins\Audit\Presentation\AuditType;

/**
 * Projects historical facts into the current viewer's permitted reading before applying selectors.
 */
final class VisibleHistory
{
    private bool $fullAccess;

    /** @var array<string, class-string<Model>> */
    private array $visibleModels = [];

    public function __construct(Registry $registry, private AuditPresentations $presentations)
    {
        abort_unless(Gate::allows('view-audit'), 403);

        $this->fullAccess = Gate::allows('view-full-audit');

        if (! $this->fullAccess) {
            foreach ($registry->resources() as $resource) {
                if (ChiefResourcePermissions::adminCanResource(auth('chief')->user(), $resource, 'view')) {
                    $class = $resource::modelClassName();
                    $this->visibleModels[(new $class)->getMorphClass()] = $class;
                }
            }
        }
    }

    /** @param array<string, mixed> $filters */
    public function paginate(array $filters = [], int $perPage = 50, int $page = 1): LengthAwarePaginator
    {
        $perPage = max(1, min(100, $perPage));
        $page = max(1, $page);
        $active = $this->hasActiveFilters($filters);
        $pageItems = collect();
        $secondaryPage = collect();
        $total = 0;
        $secondaryTotal = 0;
        $offset = ($page - 1) * $perPage;

        $this->eachVisible($filters, function (AuditEvent $event) use ($active, $offset, $perPage, &$total, &$secondaryTotal, $pageItems, $secondaryPage): void {
            if ($active || $this->priority($event) !== 'secondary') {
                if ($total >= $offset && $total < $offset + $perPage) {
                    $pageItems->push($event);
                }
                $total++;
            } else {
                if ($secondaryTotal >= $offset && $secondaryTotal < $offset + $perPage) {
                    $secondaryPage->push($event);
                }
                $secondaryTotal++;
            }
        });

        if (! $active && ($filters['show_all'] ?? false)) {
            if ($total === 0) {
                $pageItems = $secondaryPage;
                $total = $secondaryTotal;
            } elseif ($pageItems->isNotEmpty()) {
                $newest = $pageItems->first();
                $oldest = $pageItems->last();
                $secondaries = collect();
                $this->eachVisible($filters, function (AuditEvent $event) use ($newest, $oldest, $secondaries): void {
                    if ($this->priority($event) === 'secondary' && $this->notLaterThan($event, $newest) && $this->notLaterThan($oldest, $event)) {
                        $secondaries->push($event);
                    }
                });
                $pageItems = $pageItems->concat($secondaries)->sort(fn (AuditEvent $a, AuditEvent $b) => $this->notLaterThan($a, $b) ? 1 : -1)->values();
            }
        }

        return new LengthAwarePaginator($pageItems, $total, $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'query' => request()->query(),
        ]);
    }

    /** @param array<string, mixed> $filters
     * @return Collection<int, AuditEvent>
     */
    public function select(array $filters = []): Collection
    {
        $results = collect();
        $this->eachVisible($filters, static function (AuditEvent $event) use ($results): void {
            $results->push($event);
        });

        return $results;
    }

    /** @param array<string, mixed> $filters
     * @return Collection<int, AuditEvent>
     */
    public function recent(array $filters, int $limit, bool $includeSecondary): Collection
    {
        $results = collect();
        $this->eachVisible($filters, function (AuditEvent $event) use ($results, $limit, $includeSecondary): bool {
            if ($includeSecondary || $this->priority($event) !== 'secondary') {
                $results->push($event);
            }

            return $results->count() >= $limit;
        });

        return $results;
    }

    /** @param array<string, mixed> $filters */
    private function eachVisible(array $filters, callable $consume): void
    {
        $query = AuditEvent::query()->orderByDesc('occurred_at')->orderByDesc('id');

        foreach (['event_id' => 'id', 'category' => 'category', 'outcome' => 'outcome', 'actor_type' => 'actor_type'] as $filter => $column) {
            if (isset($filters[$filter]) && $filters[$filter] !== '') {
                $query->where($column, $filters[$filter]);
            }
        }
        if (isset($filters['type']) && $filters['type'] !== '') {
            if (is_array($filters['type'])) {
                $query->whereIn('type', $filters['type']);
            } else {
                $query->where('type', $filters['type']);
            }
        }
        if (isset($filters['from'])) {
            $query->where('occurred_at', '>=', Carbon::parse($filters['from'], $this->timezone())->startOfDay()->utc());
        }
        if (isset($filters['to'])) {
            $query->where('occurred_at', '<', Carbon::parse($filters['to'], $this->timezone())->addDay()->startOfDay()->utc());
        }
        if ($this->fullAccess && isset($filters['model_type']) && $filters['model_type'] !== '' && isset($filters['model_id']) && $filters['model_id'] !== '') {
            $query->whereHas('models', fn (Builder $links) => $links->where('model_type', $filters['model_type'])->where('model_id', $filters['model_id']));
        }

        if (! $this->fullAccess) {
            $actorId = (string) auth('chief')->id();
            $query->where(function (Builder $query) use ($actorId): void {
                $query->where(function (Builder $query): void {
                    foreach ($this->visibleModels as $type => $class) {
                        $query->orWhereHas('models', fn (Builder $links) => $links->where('model_type', $type)->whereIn('model_id', $class::query()->select((new $class)->getKeyName())));
                    }
                })->orWhere(fn (Builder $query) => $query->where('type', '!=', 'legacy.spatie')->where('actor_type', 'admin')->where('actor_snapshot->id', $actorId)->whereHas('models'));
            });
        }

        foreach ($query->lazy(200)->chunk(200) as $events) {
            $events = $events->values();
            $links = AuditEventModel::query()->whereIn('event_id', $events->pluck('id')->all())->orderBy('id')->get()->groupBy('event_id');
            $existing = [];

            if (! $this->fullAccess) {
                foreach ($this->visibleModels as $type => $class) {
                    $ids = $links->flatten()->where('model_type', $type)->pluck('model_id')->unique()->all();
                    if ($ids !== []) {
                        $existing[$type] = $class::query()->whereIn((new $class)->getKeyName(), $ids)->pluck((new $class)->getKeyName())->mapWithKeys(fn ($id) => [(string) $id => true])->all();
                    }
                }
            }

            foreach ($events as $event) {
                $allLinks = $links->get($event->getKey(), collect());
                $allowed = $this->fullAccess ? $allLinks : $allLinks->filter(fn (AuditEventModel $link) => isset($existing[$link->model_type][$link->model_id]))->values();
                $own = ! $this->fullAccess && $event->type !== 'legacy.spatie' && $event->actor_type === 'admin' && ($event->actor_snapshot['id'] ?? null) === (string) auth('chief')->id() && $allLinks->isNotEmpty();

                if (! $this->fullAccess && $allowed->isEmpty() && ! $own) {
                    continue;
                }

                $event->setRelation('models', $allowed);
                $event->visible_rich_data = $allowed->contains(fn (AuditEventModel $link) => $link->has_rich_data)
                    || ($event->has_rich_data && ($this->fullAccess || $allLinks->isNotEmpty() && $allowed->count() === $allLinks->count()));
                if (! $this->fullAccess || $allowed->isNotEmpty()) {
                    $event->model_snapshot = $allowed->first()?->model_snapshot;
                    $event->model_type = $allowed->first()?->model_type;
                    $event->model_id = $allowed->first()?->model_id;
                }

                if (! $this->fullAccess && ($event->type === 'legacy.spatie' || $allowed->count() !== $allLinks->count())) {
                    $event->summary = null;
                    $event->context = [];
                    $event->actor_snapshot = array_filter([
                        'name' => match ($event->actor_type) {
                            'admin' => 'Admin', 'system' => 'Systeem', default => 'Externe actor',
                        },
                    ], fn ($value) => $value !== null);
                }

                if ($this->matches($event, $filters)) {
                    if ($consume($event) === true) {
                        return;
                    }
                }
            }
        }
    }

    /** @return array<string, list<string>> */
    public function filterOptions(): array
    {
        $options = ['types' => [], 'categories' => [], 'outcomes' => [], 'model_types' => [], 'actor_types' => [], 'actors' => [], 'models' => []];
        $this->eachVisible([], static function (AuditEvent $event) use (&$options): void {
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
        $options['filters'] = $this->presentations->filters();

        return $options;
    }

    public function presentation(AuditEvent $event): AuditType
    {
        return $this->presentations->presentation(VisibleAuditEvent::fromEvent($event));
    }

    public function categoryLabel(string $category): string
    {
        return $this->presentations->categoryLabel($category);
    }

    public function priority(AuditEvent $event): string
    {
        return $this->presentation($event)->priority() === 'secondary' ? 'secondary' : 'primary';
    }

    /** @param array<string, mixed> $filters */
    public function hasActiveFilters(array $filters): bool
    {
        return collect($filters)->except(['page', 'per_page', 'show_all'])->contains(fn ($value) => is_array($value)
            ? collect($value)->contains(fn ($entry) => $entry !== null && $entry !== '')
            : $value !== null && $value !== '');
    }

    public function timezone(): string
    {
        return config('chief.audit.timezone', config('app.timezone', 'UTC'));
    }

    public function eventDetail(string $eventId): AuditEvent
    {
        $event = $this->select(['event_id' => $eventId])->first();
        abort_unless($event && ($event->context || ! $event->recorded_at->equalTo($event->occurred_at) || $this->missingMailPreview($event)), 404);

        return $event;
    }

    public function missingMailPreview(AuditEvent $event): bool
    {
        return $this->fullAccess && $event->type === 'chief.mail.invitation.sent' && ! $event->has_rich_data;
    }

    /** @return Collection<int, AuditRichData> */
    public function richData(string $eventId): Collection
    {
        $event = $this->select(['event_id' => $eventId])->first();
        abort_unless($event, 404);

        $visibleLinkIds = $event->models->pluck('id')->all();
        $allLinkCount = AuditEventModel::query()->where('event_id', $eventId)->count();
        $wholeEvent = $this->fullAccess || ($allLinkCount > 0 && count($visibleLinkIds) === $allLinkCount);
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

        if (! $this->fullAccess) {
            $class = $this->visibleModels[$link->model_type] ?? null;
            abort_unless($class && $class::query()->whereKey($link->model_id)->exists(), 404);
        }

        return $link;
    }

    /** @param array<string, mixed> $filters */
    private function matches(AuditEvent $event, array $filters): bool
    {
        if (isset($filters['event_id']) && (string) $event->getKey() !== (string) $filters['event_id']) {
            return false;
        }

        foreach (['type', 'category', 'outcome', 'actor_type'] as $key) {
            if ($key === 'type' && isset($filters[$key]) && is_array($filters[$key])) {
                if (! in_array($event->type, $filters[$key], true)) {
                    return false;
                }

                continue;
            }

            if (isset($filters[$key]) && $filters[$key] !== '' && (string) $event->$key !== (string) $filters[$key]) {
                return false;
            }
        }

        if ((isset($filters['model_type']) && $filters['model_type'] !== '' || isset($filters['model_id']) && $filters['model_id'] !== '')
            && ! $event->models->contains(fn (AuditEventModel $link) => (! isset($filters['model_type']) || $filters['model_type'] === '' || $link->model_type === $filters['model_type'])
                && (! isset($filters['model_id']) || $filters['model_id'] === '' || $link->model_id === (string) $filters['model_id']))) {
            return false;
        }

        if (isset($filters['actor']) && ($event->actor_snapshot['id'] ?? null) !== (string) $filters['actor']) {
            return false;
        }

        $day = $event->occurred_at->setTimezone($this->timezone())->toDateString();
        if (isset($filters['from']) && $day < $filters['from'] || isset($filters['to']) && $day > $filters['to']) {
            return false;
        }

        if (isset($filters['search']) && trim((string) $filters['search']) !== '') {
            $text = implode(' ', array_filter([$event->summary, $event->actor_snapshot['name'] ?? null, ...$event->models->map(fn (AuditEventModel $link) => $link->model_snapshot['name'])->all()]));

            if (mb_stripos($text, (string) $filters['search']) === false) {
                return false;
            }
        }

        foreach ($filters['filter'] ?? [] as $key => $value) {
            if ($value !== null && $value !== '' && ! $this->presentations->filters()[$key]->matches(VisibleAuditEvent::fromEvent($event), (string) $value)) {
                return false;
            }
        }

        return true;
    }

    private function notLaterThan(AuditEvent $left, AuditEvent $right): bool
    {
        return $left->occurred_at < $right->occurred_at || $left->occurred_at == $right->occurred_at && $left->getKey() <= $right->getKey();
    }
}
