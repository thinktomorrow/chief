<?php

namespace Thinktomorrow\Chief\Urls\App\Actions;

use Thinktomorrow\Chief\ManagedModels\Events\ManagedModelUrlUpdated;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\Urls\App\Actions\Redirects\AddRedirect;
use Thinktomorrow\Chief\Urls\App\Actions\Redirects\CreateRedirectTo;
use Thinktomorrow\Chief\Urls\App\Actions\Redirects\RedirectApplication;
use Thinktomorrow\Chief\Urls\App\Actions\Redirects\RetargetAllRedirectsOf;
use Thinktomorrow\Chief\Urls\App\Repositories\UrlRepository;
use Thinktomorrow\Chief\Urls\Events\UrlDeleted;
use Thinktomorrow\Chief\Urls\Exceptions\HomepageSlugNotAllowed;

class UrlApplication
{
    use WithUniqueSlug;

    private UrlRepository $repository;

    private RedirectApplication $redirectApplication;

    public function __construct(UrlRepository $repository, RedirectApplication $redirectApplication)
    {
        $this->repository = $repository;
        $this->redirectApplication = $redirectApplication;
    }

    public function create(CreateUrl $command): string
    {
        if (! $command->prependBaseUrlSegment()) {
            $this->prependBaseUrlSegment(false);
        }

        $this->validateIfHomepageSlugAllowed($command);

        $model = $command->getModelReference()->instance();
        $site = $command->getSite();
        $slug = $command->getSlug();
        $status = $command->getStatus();

        $slug = $this->composeSlug($model, $site, $slug);

        if ($this->force) {
            $this->deleteIdenticalRecordsOfModel($model, $site, $slug, null);
            $this->deleteIdenticalRecordsOfOtherModels($model, $site, $slug);
        } else {
            $this->assertSlugDoesNotExistsAsActiveUrl($site, $slug);
        }

        $this->cleanupMatchingRedirects($model, $site, $slug);

        $existingRecord = $this->repository->findActiveByModel($model->modelReference(), $site);

        $recordId = $this->repository->create($model->modelReference(), [
            'site' => $site,
            'slug' => $slug,
            'status' => $status->value,
        ]);

        // Redirect former active slug to this new one
        if ($existingRecord) {
            $this->redirectApplication->addRedirect(new AddRedirect($existingRecord->id, $recordId));
        }

        event(new ManagedModelUrlUpdated($model->modelReference()));

        return $recordId;
    }

    /**
     * Saving urls slugs in strict mode prevents identical urls to be automatically removed.
     * When set to false, this would remove the identical url records.
     */
    public function update(UpdateUrl $command): void
    {
        if (! $command->prependBaseUrlSegment()) {
            $this->prependBaseUrlSegment(false);
        }

        $this->validateIfHomepageSlugAllowed($command);

        $urlRecord = $this->repository->find($command->getId());

        $slug = $this->composeSlug($urlRecord->model, $urlRecord->site, $command->getSlug());

        if ($this->force) {
            $this->deleteIdenticalRecordsOfModel($urlRecord->model, $urlRecord->site, $slug, $urlRecord->id);
            $this->deleteIdenticalRecordsOfOtherModels($urlRecord->model, $urlRecord->site, $slug);
        } else {
            $this->assertSlugDoesNotExistsAsActiveUrl($urlRecord->site, $slug, $urlRecord->id);
        }

        $this->cleanupMatchingRedirects($urlRecord->model, $urlRecord->site, $slug, $urlRecord->id);

        $this->repository->update($command->getId(), [
            'slug' => $slug,
            'status' => $command->getStatus()->value,
        ]);

        // Create redirect if needed
        if ($urlRecord->slug != $slug) {
            $this->redirectApplication->createRedirectTo(new CreateRedirectTo($urlRecord->id, $urlRecord->slug));

            event(new ManagedModelUrlUpdated($urlRecord->model->modelReference()));
        }
    }

    private function validateIfHomepageSlugAllowed(CreateUrl|UpdateUrl $command): void
    {
        if ($command->allowHomepageSlug()) {
            return;
        }

        $slug = $command->getSlug();

        if (! $slug || $slug == '/') {
            throw new HomepageSlugNotAllowed('Slug ['.$slug.'] not allowed since it would interfere with the homepage link.');
        }
    }

    public function delete(DeleteUrl $command): void
    {
        $url = $this->repository->find($command->getId());

        $url->delete();

        if ($url->isRedirect()) {
            $this->redirectApplication->retargetAllRedirectsOf(new RetargetAllRedirectsOf($url->id, $url->redirect_id));
        }

        event(new UrlDeleted(
            $url->id,
            $url->slug,
            $url->site,
            ModelReference::make($url->model_type, $url->model_id)
        ));
    }

    public function reactivateUrl(ReactivateUrl $command): void
    {
        $record = $this->repository->find($command->getId());

        if (! $record->isRedirect()) {
            return;
        }

        $activeRecord = $this->repository->find($record->redirect_id);

        $record->redirect_id = null;
        $record->save();

        if ($activeRecord) {
            $this->redirectApplication->addRedirect(new AddRedirect($activeRecord->id, $record->id));
        }
    }

    public function changeHomepageUrl(ChangeHomepageUrl $command): void
    {
        $this->force();

        if ($existingUrl = $this->repository->findActiveByModel($command->getModelReference(), $command->getSite())) {
            $this->update(new UpdateUrl($existingUrl->id, '/', 'online', false, true));
        } else {
            $this->create(new CreateUrl($command->getModelReference(), $command->getSite(), '/', 'online', false, true));
        }
    }
}
