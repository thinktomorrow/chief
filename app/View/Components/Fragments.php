<?php

namespace Thinktomorrow\Chief\App\View\Components;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Fragments\FragmentsComponentRepository;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Managers\Register\Registry;

// Nested fragments component in sidebar (cannot use livewire for this)
class Fragments extends Component
{
    public FragmentsOwner $owner;

    private FragmentsComponentRepository $repository;
    private Collection $fragments;
    private array $allowedFragments;
    private array $sharedFragments;
    private string $locale;

    public function __construct(FragmentsOwner $owner, ?string $locale = null)
    {
        $this->repository = new FragmentsComponentRepository(app(FragmentRepository::class), app(Registry::class), $owner);
        $this->owner = $owner;
        $this->locale = $locale ?: app()->getLocale();
        $this->load();
    }

    public function load(): void
    {
        $this->fragments = $this->repository->getFragments();
        $this->allowedFragments = $this->repository->getAllowedFragments();
        $this->sharedFragments = $this->repository->getSharedFragments();
    }

    /**
     * @return Factory|View
     */
    public function render()
    {
        return view('chief-fragments::window', [
            'fragments' => $this->fragments,
            'allowedFragments' => $this->allowedFragments,
            'sharedFragments' => $this->sharedFragments,
            'manager' => $this->repository->getManager(),
            'owner' => $this->owner,
            'locale' => $this->locale,
        ]);
    }
}
