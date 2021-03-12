<?php

namespace Thinktomorrow\Chief\App\View\Components;

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

    public function __construct(FragmentsOwner $owner)
    {
        $this->repository = new FragmentsComponentRepository(app(FragmentRepository::class), app(Registry::class), $owner);
        $this->owner = $owner;
        $this->load();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('chief::manager.cards.fragments.component.fragments-nested', [
            'fragments' => $this->fragments,
            'allowedFragments' => $this->allowedFragments,
            'sharedFragments' => $this->sharedFragments,
            'manager' => $this->repository->getManager(),
        ]);
    }

    public function load(): void
    {
        $this->fragments = $this->repository->getFragments();
        $this->allowedFragments = $this->repository->getAllowedFragments();
        $this->sharedFragments = $this->repository->getSharedFragments();
    }
}
