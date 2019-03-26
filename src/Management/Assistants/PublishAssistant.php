<?php


namespace Thinktomorrow\Chief\Management\Assistants;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Audit\Audit;
use Thinktomorrow\Chief\Management\Assistant;
use Thinktomorrow\Chief\Management\Manager;
use Thinktomorrow\Chief\Management\Managers;

class PublishAssistant implements Assistant
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

    public static function key(): string
    {
        return 'publish';
    }

    public function isPublished(): bool
    {
        return $this->model->isPublished();
    }

    public function publishedAt(): Carbon
    {
        return $this->model->Published_at;
    }

    public function publish()
    {
        $this->model->publish();

        Audit::activity()
            ->performedOn($this->model)
            ->log('published');
    }

    public function draft()
    {
        $this->model->draft();

        Audit::activity()
            ->performedOn($this->model)
            ->log('draft');
    }

    public function findAll(): Collection
    {
        return $this->model->published()->get()->map(function ($model) {
            return $this->managers->findByModel($model);
        });
    }

    public function route($verb)
    {
        $modelRoutes = [
            'publish'   => route('chief.back.assistants.publish', [$this->manager->details()->key, $this->manager->model()->id]),
            'draft'     => route('chief.back.assistants.draft', [$this->manager->details()->key, $this->manager->model()->id]),
        ];

        return $modelRoutes[$verb] ?? null;
    }
}
