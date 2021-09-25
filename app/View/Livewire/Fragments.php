<?php

namespace Thinktomorrow\Chief\App\View\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Fragments\FragmentsComponentRepository;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Managers\Register\Registry;

class Fragments extends Component
{
    /** @var FragmentsComponentRepository */
    private $repository;

    public FragmentsOwner $owner;
    public $class;
    private Collection $fragments;
    private array $allowedFragments;
    private array $sharedFragments;

    public function mount(FragmentsOwner $owner): void
    {
        $this->owner = $owner;

        $this->reload();
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        return view('chief::manager.windows.fragments.component.fragments-main', [
            'fragments' => $this->fragments,
            'allowedFragments' => $this->allowedFragments,
            'sharedFragments' => $this->sharedFragments,
            'manager' => $this->repository->getManager(),
        ]);
    }

    public function reload(): void
    {
        // Init the repository here so it is always mounted when reloading the component
        $this->repository = new FragmentsComponentRepository(app(FragmentRepository::class), app(Registry::class), $this->owner);

        $this->fragments = $this->repository->getFragments();
        $this->allowedFragments = $this->repository->getAllowedFragments();
        $this->sharedFragments = $this->repository->getSharedFragments();

        $this->emit('fragmentsReloaded');
    }
}
