<?php declare(strict_types=1);

namespace Thinktomorrow\Chief\Management\Assistants;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Management\Manager;
use Thinktomorrow\Chief\Management\Managers;
use Thinktomorrow\Chief\Urls\ProvidesUrl\ProvidesUrl;
use Thinktomorrow\Chief\Management\Application\PublishManagedModel;
use Thinktomorrow\Chief\Management\Exceptions\NotAllowedManagerRoute;
use Thinktomorrow\Chief\Management\Application\UnpublishManagedModel;

class PublishAssistant implements Assistant
{
    private $manager;

    /** @var Managers */
    private $managers;

    public function __construct(Managers $managers)
    {
        $this->managers = $managers;
    }

    public function manager(Manager $manager)
    {
        $this->manager = $manager;
    }

    public static function key(): string
    {
        return 'publish';
    }

    public function isPublished(): bool
    {
        return $this->manager->existingModel()->isPublished();
    }

    public function isDraft(): bool
    {
        return $this->manager->existingModel()->isDraft();
    }

    public function publishedAt(): Carbon
    {
        return $this->manager->existingModel()->published_at;
    }

    public function publish()
    {
        $this->guard('publish');

        app(PublishManagedModel::class)->handle($this->manager->existingModel());

        return redirect()->to($this->manager->route('edit'))->with('messages.success', $this->manager->details()->title .' is online gezet.');
    }

    public function unpublish()
    {
        $this->guard('unpublish');

        app(UnpublishManagedModel::class)->handle($this->manager->existingModel());

        return redirect()->to($this->manager->route('edit'))->with('messages.success', $this->manager->details()->title .' is offline gehaald.');
    }

    public function findAll(): Collection
    {
        return $this->manager->existingModel()->published()->get()->map(function ($model) {
            return $this->managers->findByModel($model);
        });
    }

    public function route($verb): ?string
    {
        $modelRoutes = [
            'publish' => route('chief.back.assistants.update', [$this->key(), 'publish', $this->manager->details()->key, $this->manager->existingModel()->id]),
            'unpublish' => route('chief.back.assistants.update', [$this->key(), 'unpublish', $this->manager->details()->key, $this->manager->existingModel()->id]),
        ];

        return $modelRoutes[$verb] ?? null;
    }

    public function can($verb): bool
    {
        return !is_null($this->route($verb));
    }

    private function guard($verb): Assistant
    {
        if (! $this->can($verb)) {
            NotAllowedManagerRoute::notAllowedVerb($verb, $this->manager);
        }

        return $this;
    }

    public function hasPreviewUrl(): bool
    {
        return $this->manager->existingModel() instanceof ProvidesUrl && $this->previewUrl() != '?preview-mode';
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
        return $this->manager->existingModel()->previewUrl();
    }
}
