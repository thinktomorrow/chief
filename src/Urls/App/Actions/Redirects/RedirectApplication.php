<?php

namespace Thinktomorrow\Chief\Urls\App\Actions\Redirects;

use Thinktomorrow\Chief\Urls\App\Repositories\UrlRepository;
use Thinktomorrow\Chief\Urls\Exceptions\RedirectUrlAlreadyExists;
use Thinktomorrow\Chief\Urls\Exceptions\UrlRecordNotFound;
use Thinktomorrow\Chief\Urls\Models\LinkStatus;
use Thinktomorrow\Chief\Urls\Models\UrlRecord;
use Thinktomorrow\Url\Url;

class RedirectApplication
{
    private UrlRepository $repository;

    public function __construct(UrlRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createRedirectTo(CreateRedirectTo $command): string
    {
        $targetRecord = $this->repository->find($command->getTargetId());

        $parsedUrl = Url::fromString($command->getRedirectSlug());

        // Strip out the slashes and possible host/scheme reference.
        $redirectUrl =
            ($parsedUrl->hasPath() ? $parsedUrl->getPath() : '').
            ($parsedUrl->hasQuery() ? '?'.$parsedUrl->getQuery() : '').
            ($parsedUrl->hasHash() ? '#'.$parsedUrl->getHash() : '');

        if ($this->repository->findBySlug($redirectUrl, $targetRecord->site)) {
            throw new RedirectUrlAlreadyExists($redirectUrl.' [site: '.$targetRecord->site.'] already exists as url');
        }

        $redirectRecordId = $this->repository->create($targetRecord->model->modelReference(), [
            'site' => $targetRecord->site,
            'slug' => $redirectUrl,
            'status' => LinkStatus::online->value,
        ]);

        $this->addRedirect(new AddRedirect($redirectRecordId, $targetRecord->id));

        return $redirectRecordId;
    }

    public function createRedirectFromSlugs(CreateRedirectFromSlugs $command): void
    {
        $targetRecord = $this->repository->findBySlug($command->getTargetSlug(), $command->getSite());

        if (! $targetRecord) {
            throw new UrlRecordNotFound('No url found by slug ['.$command->getTargetSlug().'] for site ['.$command->getSite().']');
        }

        $this->createRedirectTo(new CreateRedirectTo($targetRecord->id, $command->getRedirectSlug()));
    }

    public function addRedirect(AddRedirect $command): void
    {
        if ($command->getRedirectId() === $command->getTargetId()) {
            throw new \InvalidArgumentException('Failed to create a redirect. Cannot redirect to itself [id: '.$command->getRedirectId());
        }

        $redirectRecord = $this->repository->find($command->getRedirectId());

        $redirectRecord->redirect_id = $command->getTargetId();
        $redirectRecord->save();
    }

    public function retargetAllRedirectsOf(RetargetAllRedirectsOf $command): void
    {
        $redirects = UrlRecord::where('redirect_id', $command->getRecordId())->get();

        foreach ($redirects as $redirect) {
            $redirect->redirect_id = $command->getTargetId();
            $redirect->save();

            $this->retargetAllRedirectsOf(new RetargetAllRedirectsOf($redirect->id, $command->getTargetId()));
        }
    }
}
