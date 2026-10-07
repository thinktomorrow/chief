<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\App\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Thinktomorrow\Chief\Admin\Authorization\ChiefResourcePermissions;
use Thinktomorrow\Chief\App\Http\Controllers\Controller;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Plugins\Audit\AuditEvent;
use Thinktomorrow\Chief\Plugins\Audit\AuditEventModel;

final class HistoryController extends Controller
{
    public function index(Registry $registry): View
    {
        abort_unless(Gate::allows('view-full-audit') || Gate::allows('view-related-audit'), 403);

        $fullAccess = Gate::allows('view-full-audit');
        $query = AuditEvent::query()->orderByDesc('occurred_at')->orderByDesc('id');

        if ($fullAccess) {
            $query->with('models');
        } else {
            $visibleModels = $this->visibleModels($registry);
            $actorId = (string) auth('chief')->id();
            $query->where(function (Builder $query) use ($visibleModels, $actorId): void {
                $query->where(function (Builder $query) use ($visibleModels): void {
                    foreach ($visibleModels as $type => $modelClass) {
                        $query->orWhereHas('models', fn (Builder $models) => $models->where('model_type', $type)->whereIn('model_id', $modelClass::query()->select((new $modelClass)->getKeyName())));
                    }
                })->orWhere(function (Builder $query) use ($actorId): void {
                    $query->where('actor_type', 'admin')->where('actor_snapshot->id', $actorId)->whereHas('models');
                });
            });
        }

        $events = $query->paginate(50);

        if (! $fullAccess) {
            $eventIds = $events->getCollection()->modelKeys();
            $links = AuditEventModel::query()->whereIn('event_id', $eventIds)->where(function (Builder $query) use ($visibleModels): void {
                $query->whereIn('id', []);
                foreach ($visibleModels as $type => $modelClass) {
                    $query->orWhere(fn (Builder $link) => $link->where('model_type', $type)->whereIn('model_id', $modelClass::query()->select((new $modelClass)->getKeyName())));
                }
            })->orderBy('id')->get()->groupBy('event_id');

            $events->getCollection()->each(function (AuditEvent $event) use ($links): void {
                $allowedLinks = $links->get($event->getKey(), collect());
                $event->setRelation('models', $allowedLinks);
                $visible = $allowedLinks->first();
                $event->model_snapshot = $visible?->model_snapshot;

                if (! $visible) {
                    $event->summary = null;
                }
            });
        }

        return view('chief-audit::index', ['events' => $events]);
    }

    public function details(Registry $registry, string $event, string $model): View
    {
        abort_unless(Gate::allows('view-full-audit') || Gate::allows('view-related-audit'), 403);

        $event = AuditEvent::query()->findOrFail($event);
        $model = AuditEventModel::query()->where('event_id', $event->getKey())->findOrFail($model);
        abort_unless($model->changes, 404);

        if (! Gate::allows('view-full-audit')) {
            $visibleModels = $this->visibleModels($registry);
            $modelClass = $visibleModels[$model->model_type] ?? null;
            abort_unless($modelClass && $modelClass::query()->whereKey($model->model_id)->exists(), 404);
        }

        return view('chief-audit::details', ['event' => $event, 'model' => $model]);
    }

    /** @return array<string, class-string<Model>> */
    private function visibleModels(Registry $registry): array
    {
        $visibleModels = [];

        foreach ($registry->resources() as $resource) {
            if (! ChiefResourcePermissions::adminCanResource(auth('chief')->user(), $resource, 'view')) {
                continue;
            }

            $modelClass = $resource::modelClassName();
            $visibleModels[(new $modelClass)->getMorphClass()] = $modelClass;
        }

        return $visibleModels;
    }
}
