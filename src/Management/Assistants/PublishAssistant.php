<?php


namespace Thinktomorrow\Chief\Management\Assistants;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Audit\Audit;
use Thinktomorrow\Chief\Management\Manager;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\Management\Exceptions\NotAllowedManagerRoute;
use Thinktomorrow\Chief\Urls\ProvidesUrl\ProvidesUrl;

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

    public function hasPreviewUrl(): bool
    {
        return $this->model instanceof ProvidesUrl;
    }

    public function publicationStatusAsLabel($plain = false)
    {
        $label = $this->publicationStatusAsPlainLabel();

        $class = $this->isPublished() ? 'text-success' : 'text-error';

        $statusAsLabel = '<span class="'. $class .'"><em>' . $label . '</em></span>';

        if(!$plain && $this->hasPreviewUrl())
        {
            $statusAsLabel =  '<a href="'.$this->previewUrl().'" target="_blank">'. $statusAsLabel .'</a>';
        }

        return $statusAsLabel;
    }

    private function publicationStatusAsPlainLabel()
    {
        if ($this->isPublished()) {
            return 'online';
        }elseif ($this->isDraft()) {
            return 'offline';
        }elseif ($this->manager->isAssistedBy('archive') && $this->manager->assistant('archive')->isArchived()) {
            return 'gearchiveerd';
        }

        return '-';
    }

    public function previewUrl(): string
    {
        if (!$this->hasPreviewUrl()) {
            return '';
        }

        return $this->model->previewUrl();
    }
}
