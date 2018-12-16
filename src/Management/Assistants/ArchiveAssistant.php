<?php


namespace Thinktomorrow\Chief\Management\Assistants;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Audit\Audit;
use Thinktomorrow\Chief\Management\Assistant;
use Thinktomorrow\Chief\Management\Manager;
use Thinktomorrow\Chief\Management\Managers;

class ArchiveAssistant implements Assistant
{
    private $manager;

    private $model;

    /** @var Managers */
    private $managers;

    public function __construct(Managers $managers)
    {
        $this->managers = $managers;
    }

    public function manager(Manager $manager)
    {
        $this->manager = $manager;
        $this->model = $manager->model();
    }

    public function isArchived(): bool
    {
        return $this->model->isArchived();
    }

    public function archivedAt(): Carbon
    {
        return $this->model->archived_at;
    }

    public function archive()
    {
        $this->model->archive();

        Audit::activity()
            ->performedOn($this->model)
            ->log('archived');
    }

    public function unarchive()
    {
        $this->model->unarchive();

        Audit::activity()
            ->performedOn($this->model)
            ->log('unarchived');
    }

    public function findAll(): Collection
    {
        return $this->model->archived()->get()->map(function ($model){
            return $this->managers->findByModel($model);
        });
    }

    public function route($verb)
    {
        $routes = [
            'index' => route('chief.back.assistants.archive-index', [$this->manager->details()->key]),
        ];

        if(array_key_exists($verb, $routes)) return $routes[$verb] ?? null;

        $modelRoutes = [
            'archive' => route('chief.back.assistants.archive', [$this->manager->details()->key, $this->manager->model()->id]),
            'unarchive' => route('chief.back.assistants.unarchive', [$this->manager->details()->key, $this->manager->model()->id]),
        ];

        return isset($modelRoutes[$verb]) ? $modelRoutes[$verb] : null;
    }
}
