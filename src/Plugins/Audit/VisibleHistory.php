<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Thinktomorrow\Chief\Admin\Authorization\ChiefResourcePermissions;
use Thinktomorrow\Chief\Managers\Register\Registry;

/**
 * Projects historical facts into the current viewer's permitted reading before applying selectors.
 */
final class VisibleHistory
{
    private bool $fullAccess;

    /** @var array<string, class-string<Model>> */
    private array $visibleModels = [];

    public function __construct(Registry $registry)
    {
        abort_unless(Gate::allows('view-full-audit') || Gate::allows('view-related-audit'), 403);

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
        $results = $this->select($filters);
        $perPage = max(1, min(100, $perPage));
        $page = max(1, $page);

        return new LengthAwarePaginator($results->slice(($page - 1) * $perPage, $perPage)->values(), $results->count(), $perPage, $page, [
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
        $query = AuditEvent::query()->orderByDesc('occurred_at')->orderByDesc('id');

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
                        'id' => $event->actor_snapshot['id'] ?? null,
                    ], fn ($value) => $value !== null);
                }

                if ($this->matches($event, $filters)) {
                    $results->push($event);
                }
            }
        }

        return $results;
    }

    /** @return array<string, list<string>> */
    public function filterOptions(): array
    {
        $events = $this->select();

        return [
            'types' => $events->pluck('type')->unique()->values()->all(),
            'categories' => $events->pluck('category')->unique()->values()->all(),
            'outcomes' => $events->pluck('outcome')->filter()->unique()->values()->all(),
            'model_types' => $events->flatMap(fn (AuditEvent $event) => $event->models->pluck('model_type'))->unique()->values()->all(),
        ];
    }

    public function detail(string $eventId, string $linkId): AuditEventModel
    {
        $event = AuditEvent::query()->findOrFail($eventId);
        $link = AuditEventModel::query()->where('event_id', $event->getKey())->findOrFail($linkId);
        abort_unless($link->changes, 404);

        if (! $this->fullAccess) {
            $class = $this->visibleModels[$link->model_type] ?? null;
            abort_unless($class && $class::query()->whereKey($link->model_id)->exists(), 404);
        }

        return $link;
    }

    /** @param array<string, mixed> $filters */
    private function matches(AuditEvent $event, array $filters): bool
    {
        foreach (['type', 'category', 'outcome', 'actor_type'] as $key) {
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

        if (isset($filters['from']) && $event->occurred_at->toDateString() < $filters['from'] || isset($filters['to']) && $event->occurred_at->toDateString() > $filters['to']) {
            return false;
        }

        if (isset($filters['search']) && trim((string) $filters['search']) !== '') {
            $text = implode(' ', array_filter([$event->summary, $event->actor_snapshot['name'] ?? null, ...$event->models->map(fn (AuditEventModel $link) => $link->model_snapshot['name'])->all()]));

            return mb_stripos($text, (string) $filters['search']) !== false;
        }

        return true;
    }
}
