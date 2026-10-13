<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\Reading;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;
use Thinktomorrow\Chief\Admin\Authorization\ChiefResourcePermissions;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Plugins\Audit\Persistence\AuditEvent;
use Thinktomorrow\Chief\Plugins\Audit\Persistence\AuditEventModel;

/** Projects stored events into the current viewer's permitted reading before applying filters. */
final class HistoryRead
{
    private bool $fullAccess;

    /** @var array<string, class-string<Model>> */
    private array $visibleModels = [];

    public function __construct(Registry $registry)
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

    /** @param callable(AuditEvent): (bool|void) $consume */
    public function each(HistoryFilters $filters, callable $consume): void
    {
        $query = AuditEvent::query()->orderByDesc('occurred_at')->orderByDesc('id');
        $this->applyQueryFilters($query, $filters);
        $this->applyVisibilityConstraints($query);

        foreach ($query->lazy(200)->chunk(200) as $events) {
            $events = $events->values();
            $links = AuditEventModel::query()->whereIn('event_id', $events->pluck('id')->all())->orderBy('id')->get()->groupBy('event_id');
            $existing = $this->existingModelIds($links);

            foreach ($events as $event) {
                $visibleEvent = $this->projectEvent($event, $links->get($event->getKey(), collect()), $existing);

                // Search and model filters must see only the projected, permitted data.
                if ($visibleEvent && $filters->matches($visibleEvent, $this->timezone()) && $consume($visibleEvent) === true) {
                    return;
                }
            }
        }
    }

    private function applyQueryFilters(Builder $query, HistoryFilters $filters): void
    {
        foreach (['event_id' => 'id', 'category' => 'category', 'outcome' => 'outcome', 'actor_type' => 'actor_type'] as $filter => $column) {
            if ($filters->hasValue($filter)) {
                $query->where($column, $filters->value($filter));
            }
        }
        if ($filters->hasValue('type')) {
            if (is_array($filters->value('type'))) {
                $query->whereIn('type', $filters->value('type'));
            } else {
                $query->where('type', $filters->value('type'));
            }
        }
        if ($filters->value('from') !== null) {
            $query->where('occurred_at', '>=', Carbon::parse($filters->value('from'), $this->timezone())->startOfDay()->utc());
        }
        if ($filters->value('to') !== null) {
            $query->where('occurred_at', '<', Carbon::parse($filters->value('to'), $this->timezone())->addDay()->startOfDay()->utc());
        }
        if ($this->fullAccess && $filters->hasValue('model_type') && $filters->hasValue('model_id')) {
            $query->whereHas('models', fn (Builder $links) => $links->where('model_type', $filters->value('model_type'))->where('model_id', $filters->value('model_id')));
        }
    }

    private function applyVisibilityConstraints(Builder $query): void
    {
        if ($this->fullAccess) {
            return;
        }

        $actorId = (string) auth('chief')->id();
        $query->where(function (Builder $query) use ($actorId): void {
            $query->where(function (Builder $query): void {
                foreach ($this->visibleModels as $type => $class) {
                    $query->orWhereHas('models', fn (Builder $links) => $links->where('model_type', $type)->whereIn('model_id', $class::query()->select((new $class)->getKeyName())));
                }
            })->orWhere(fn (Builder $query) => $query->where('type', '!=', 'legacy.spatie')->where('actor_type', 'admin')->where('actor_snapshot->id', $actorId)->whereHas('models'));
        });
    }

    /**
     * @param  Collection<int|string, Collection<int, AuditEventModel>>  $links
     * @return array<string, array<string, bool>>
     */
    private function existingModelIds(Collection $links): array
    {
        $existing = [];

        if (! $this->fullAccess) {
            foreach ($this->visibleModels as $type => $class) {
                $ids = $links->flatten()->where('model_type', $type)->pluck('model_id')->unique()->all();
                if ($ids !== []) {
                    $existing[$type] = $class::query()->whereIn((new $class)->getKeyName(), $ids)->pluck((new $class)->getKeyName())->mapWithKeys(fn ($id) => [(string) $id => true])->all();
                }
            }
        }

        return $existing;
    }

    /**
     * @param  Collection<int, AuditEventModel>  $allLinks
     * @param  array<string, array<string, bool>>  $existing
     */
    private function projectEvent(AuditEvent $event, Collection $allLinks, array $existing): ?AuditEvent
    {
        $allowed = $this->fullAccess ? $allLinks : $allLinks->filter(fn (AuditEventModel $link) => isset($existing[$link->model_type][$link->model_id]))->values();
        $own = ! $this->fullAccess && $event->type !== 'legacy.spatie' && $event->actor_type === 'admin' && ($event->actor_snapshot['id'] ?? null) === (string) auth('chief')->id() && $allLinks->isNotEmpty();

        if (! $this->fullAccess && $allowed->isEmpty() && ! $own) {
            return null;
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

        return $event;
    }

    /** @return Collection<int, AuditEvent> */
    public function select(HistoryFilters $filters): Collection
    {
        $results = collect();
        $this->each($filters, static function (AuditEvent $event) use ($results): void {
            $results->push($event);
        });

        return $results;
    }

    public function fullAccess(): bool
    {
        return $this->fullAccess;
    }

    public function canViewModel(string $type, string $id): bool
    {
        $class = $this->visibleModels[$type] ?? null;

        return $this->fullAccess || ($class && $class::query()->whereKey($id)->exists());
    }

    public function timezone(): string
    {
        return config('chief-audit.timezone') ?? config('app.timezone', 'UTC');
    }
}
