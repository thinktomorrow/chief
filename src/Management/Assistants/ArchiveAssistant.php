<?php


namespace Thinktomorrow\Chief\Management\Assistants;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\States\State\StatefulContract;
use Thinktomorrow\Chief\Management\Application\ArchiveManagedModel;
use Thinktomorrow\Chief\Management\Exceptions\NotAllowedManagerRoute;
use Thinktomorrow\Chief\Management\Manager;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\Management\Application\UnarchiveManagedModel;

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
        $this->manager  = $manager;
        $this->model    = $manager->model();

        if(!$this->model instanceof StatefulContract){
            throw new \InvalidArgumentException('ArchiveAssistant requires the model to implement the StatefulContract.');
        }
    }

    public static function key(): string
    {
        return 'archive';
    }

    public function isArchived(): bool
    {
        return $this->model->isArchived();
    }

    public function archivedAt(): Carbon
    {
        return new Carbon($this->model->archived_at);
    }

    public function archive()
    {
        $this->guard('archive');

        app(ArchiveManagedModel::class)->handle($this->model);
    }

    public function unarchive()
    {
        $this->guard('unarchive');

        app(UnArchiveManagedModel::class)->handle($this->model);
    }

    public function findAll(): Collection
    {
        return $this->model->archived()->get()->map(function ($model) {
            return $this->managers->findByModel($model);
        });
    }

    public function route($verb): ?string
    {
        $routes = [
            'index' => route('chief.back.assistants.archive-index', [$this->manager->details()->key]),
        ];

        if (array_key_exists($verb, $routes)) {
            return $routes[$verb] ?? null;
        }

        $modelRoutes = [
            'archive'   => route('chief.back.assistants.archive', [$this->manager->details()->key, $this->manager->model()->id]),
            'unarchive' => route('chief.back.assistants.unarchive', [$this->manager->details()->key, $this->manager->model()->id]),
        ];

        return isset($modelRoutes[$verb]) ? $modelRoutes[$verb] : null;
    }

    public function can($verb): bool
    {
        return !is_null($this->route($verb));
    }

    public function guard($verb): Assistant
    {
        if (! $this->can($verb)) {
            NotAllowedManagerRoute::notAllowedVerb($verb, $this->manager);
        }

        return $this;
    }
}
