<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Plugins\Audit\App\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
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

        $query = AuditEvent::query()->orderByDesc('occurred_at')->orderByDesc('id');

        if (! Gate::allows('view-full-audit')) {
            $visibleModels = [];

            foreach ($registry->resources() as $resource) {
                if (! ChiefResourcePermissions::adminCanResource(auth('chief')->user(), $resource, 'view')) {
                    continue;
                }

                $modelClass = $resource::modelClassName();
                $modelType = (new $modelClass)->getMorphClass();
                $visibleModels[$modelType] = $modelClass;
            }

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

        if (! Gate::allows('view-full-audit')) {
            $eventIds = $events->getCollection()->modelKeys();
            $links = AuditEventModel::query()->whereIn('event_id', $eventIds)->where(function (Builder $query) use ($visibleModels): void {
                $query->whereIn('id', []);
                foreach ($visibleModels as $type => $modelClass) {
                    $query->orWhere(fn (Builder $link) => $link->where('model_type', $type)->whereIn('model_id', $modelClass::query()->select((new $modelClass)->getKeyName())));
                }
            })->orderBy('id')->get()->groupBy('event_id');

            $events->getCollection()->each(function (AuditEvent $event) use ($links): void {
                $visible = $links->get($event->getKey())?->first();
                $event->model_snapshot = $visible?->model_snapshot;

                if (! $visible) {
                    $event->summary = null;
                }
            });
        }

        return view('chief-audit::index', ['events' => $events]);
    }
}
