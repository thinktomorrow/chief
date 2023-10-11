<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Resource\Models;

use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Managers\Manager;
use Thinktomorrow\Chief\Managers\Register\Registry;

class FragmentsComponentRepository
{
    private FragmentRepository $fragmentRepository;
    private FragmentsOwner $owner;
    private Registry $registry;

    public function __construct(FragmentRepository $fragmentRepository, Registry $registry, FragmentsOwner $owner)
    {
        $this->fragmentRepository = $fragmentRepository;
        $this->registry = $registry;
        $this->owner = $owner;
    }

    public function getFragments(string $locale): Collection
    {
        return $this->fragmentRepository->getByOwner($this->owner->ownerModel(), $locale);
    }

    public function getManager(): Manager
    {
        return $this->registry->findManagerByModel($this->owner::class);
    }

    public function getAllowedFragments(): array
    {
        return array_map(function ($fragmentableClass) {
            return app($fragmentableClass);
        }, $this->owner->allowedFragments());
    }

    public function getShareableFragments(string $locale): array
    {
        $fragmentModelIds = $this->getFragments($locale)->map(fn ($fragment) => $fragment->fragmentModel())->pluck('id')->toArray();

        return $this->fragmentRepository->getShareableFragments($this->owner)->map(function ($fragment) use ($fragmentModelIds) {
            return [
                'fragment' => $fragment,
                'is_already_selected' => in_array($fragment->getFragmentId(), $fragmentModelIds),
            ];
        })->all();
    }
}
