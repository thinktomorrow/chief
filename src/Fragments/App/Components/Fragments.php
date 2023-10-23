<?php

namespace Thinktomorrow\Chief\Fragments\App\Components;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Fragments\Resource\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Resource\Models\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Resource\Models\FragmentsComponentRepository;
use Thinktomorrow\Chief\Managers\Register\Registry;

// Nested fragments component in sidebar (cannot use livewire for this)
class Fragments extends Component
{
    public FragmentsOwner $owner;
    public string $contextId;

    private FragmentsComponentRepository $repository;
    private Collection $fragments;
    private array $allowedFragments;
    private array $sharedFragments;
    private $context;

    public function __construct(string $contextId)
    {
        $this->contextId = $contextId;
        $this->context = ContextModel::find($contextId);
        $this->owner = $this->context->getOwner();

        $this->repository = new FragmentsComponentRepository(app(FragmentRepository::class), app(Registry::class), $this->owner);
        $this->load();
    }

    public function load(): void
    {
        $this->fragments = $this->repository->getFragments($this->contextId);
        $this->allowedFragments = $this->repository->getAllowedFragments();
        $this->sharedFragments = $this->repository->getShareableFragments($this->contextId);
    }

    /**
     * @return Factory|View
     */
    public function render()
    {
        return view('chief-fragments::index', [
            'context' => $this->context,
            'fragments' => $this->fragments,
            'allowedFragments' => $this->allowedFragments,
            'sharedFragments' => $this->sharedFragments,
//            'manager' => $this->owner instanceof Fragmentable ? null : $this->repository->getManager(),
            'owner' => $this->owner,
        ]);
    }
}
