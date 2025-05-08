<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Urls\App\Listeners;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUrlUpdated;
use Thinktomorrow\Chief\Shared\Concerns\Nestable\Nestable;
use Thinktomorrow\Chief\Site\Visitable\Visitable;
use Thinktomorrow\Chief\Sites\ChiefSites;
use Thinktomorrow\Chief\Urls\App\Actions\UpdateUrl;
use Thinktomorrow\Chief\Urls\App\Actions\UrlApplication;
use Thinktomorrow\Chief\Urls\App\Queries\StripBaseUrlSegments;
use Thinktomorrow\Chief\Urls\App\Repositories\UrlRepository;

class PropagateUrlChange
{
    private array $locales;

    private UrlRepository $repository;

    private UrlApplication $application;

    public function __construct(UrlApplication $application, UrlRepository $repository)
    {
        $this->locales = ChiefSites::locales();
        $this->repository = $repository;
        $this->application = $application;
    }

    public function onManagedModelUrlUpdated(ManagedModelUrlUpdated $event): void
    {
        $model = $event->modelReference->instance();

        if (! $model instanceof Nestable) {
            return;
        }

        $this->handle($model);
    }

    /**
     * When a nestable url gets saved, we'll make sure that all
     * underlying children will have their urls updated.
     */
    public function handle(Nestable&Visitable $model, ?Model $formerParent = null): void
    {
        $parentModel = $model->getParent();

        // TODO: how to set locales per page??? Now we always take the general locales from chief
        foreach ($this->locales as $locale) {

            $strippableSlugs = [];

            // In case of a parent switch, allow to replace the base url segment belonging to a former parent.
            if ($formerParent && $formerParentUrl = $this->repository->findActiveByModel($formerParent->modelReference(), $locale)) {
                $strippableSlugs[] = $formerParentUrl->slug;
            }

            // In case of parent changing its url, allow to replace the former base url segment belonging the parent.
            if ($parentModel && $parentUrl = $this->repository->findActiveByModel($parentModel->modelReference(), $locale)) {
                $strippableSlugs[] = $parentUrl->slug;
                $strippableSlugs[] = $this->repository->findRecentRedirectByModel($parentModel->modelReference(), $locale)?->slug;
            }

            if ($record = $this->repository->findActiveByModel($model->modelReference(), $locale)) {
                $strippedSlug = StripBaseUrlSegments::strip($model, $locale, $record->slug, $strippableSlugs);

                $this->application->update(new UpdateUrl((string) $record->id, $strippedSlug, $record->status));
            }
        }

        foreach ($model->getChildren() as $child) {
            $this->handle($child);
        }
    }
}
