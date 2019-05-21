<?php


namespace Thinktomorrow\Chief\Management\Assistants;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Audit\Audit;
use Thinktomorrow\Chief\Management\Manager;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\Concerns\ProvidesUrl\ProvidesUrl;
use Thinktomorrow\Chief\Management\Exceptions\NotAllowedManagerRoute;

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

    public function isDraft(): bool
    {
        return $this->model->isDraft();
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

    public function route($verb): ?string
    {
        $modelRoutes = [
            'publish'   => route('chief.back.assistants.publish', [$this->manager->details()->key, $this->manager->model()->id]),
            'draft'     => route('chief.back.assistants.draft', [$this->manager->details()->key, $this->manager->model()->id]),
        ];

        return $modelRoutes[$verb] ?? null;
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

    public function previewUrl(): string
    {
        if (! $this->model instanceof ProvidesUrl) {
            throw new \Exception('Managed model ' . get_class($this->model) . ' should implement ' . ProvidesUrl::class);
        }

        return $this->model->previewUrl();
    }

    public function publicationStatusAsLabel($plain = false)
    {
        $label = $this->publicationStatusAsPlainLabel();

        if ($plain) {
            return $label;
        }

        if ($this->isPublished()) {
            return '<a href="'.$this->previewUrl().'" target="_blank"><em>'.$label.'</em></a>';
        }

        if ($this->isDraft()) {
            return '<a href="'.$this->previewUrl().'" target="_blank" class="text-error"><em>'.$label.'</em></a>';
        }

        return '<span><em>'.$label.'</em></span>';
    }

    private function publicationStatusAsPlainLabel()
    {
        if ($this->isPublished()) {
            return 'online';
        }

        if ($this->isDraft()) {
            return 'offline';
        }

        if ($this->manager->isAssistedBy('archive') && $this->manager->assistant('archive')->isArchived()) {
            return 'gearchiveerd';
        }

        return '-';
    }
}
