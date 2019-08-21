<?php


namespace Thinktomorrow\Chief\Management\Assistants;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Audit\Audit;
use Thinktomorrow\Chief\Management\Manager;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\States\State\StatefulContract;
use Thinktomorrow\Chief\Management\Application\PublishManagedModel;
use Thinktomorrow\Chief\Management\Exceptions\NotAllowedManagerRoute;
use Thinktomorrow\Chief\Urls\ProvidesUrl\ProvidesUrl;
use Thinktomorrow\Chief\Management\Application\UnpublishManagedModel;

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

        if(!$this->model instanceof StatefulContract){
            throw new \InvalidArgumentException('PublishAssistant requires the model to implement the StatefulContract.');
        }
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
        $this->guard('publish');

        app(PublishManagedModel::class)->handle($this->model);
    }

    public function unpublish()
    {
        $this->guard('unpublish');

        app(UnpublishManagedModel::class)->handle($this->model);
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
            'unpublish'     => route('chief.back.assistants.unpublish', [$this->manager->details()->key, $this->manager->model()->id]),
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
        return $this->model instanceof ProvidesUrl && $this->previewUrl() != '?preview-mode';
    }

    public function publicationStatusAsLabel($plain = false)
    {
        $label = $this->publicationStatusAsPlainLabel();
        $class = '';

        if ($this->isPublished()) {
            $class = 'text-success';
        } elseif ($this->isDraft()) {
            $class = 'text-error';
        } elseif ($this->manager->isAssistedBy('archive') && $this->manager->assistant('archive')->isArchived()) {
            $class = 'text-warning';
        }

        $statusAsLabel = '<span class="font-bold '. $class .'"><em>' . $label . '</em></span>';

        if (!$plain && $this->hasPreviewUrl()) {
            $statusAsLabel =  '<a href="'.$this->previewUrl().'" target="_blank">'. $statusAsLabel .'</a>';
        }

        return $statusAsLabel;
    }

    private function publicationStatusAsPlainLabel()
    {
        if ($this->isPublished()) {
            return 'online';
        } elseif ($this->isDraft()) {
            return 'offline';
        } elseif ($this->manager->isAssistedBy('archive') && $this->manager->assistant('archive')->isArchived()) {
            return 'gearchiveerd';
        }

        return '-';
    }

    public function previewUrl(): string
    {
        return $this->model->previewUrl();
    }
}
