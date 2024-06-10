<?php

namespace Thinktomorrow\Chief\Fragments\UI\Components;

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Thinktomorrow\Chief\Fragments\App\Queries\GetShareableFragments;
use Thinktomorrow\Chief\Fragments\ContextOwner;
use Thinktomorrow\Chief\Fragments\Models\ContextModel;
use Thinktomorrow\Chief\Fragments\Repositories\ContextOwnerRepository;
use Thinktomorrow\Chief\Fragments\Repositories\FragmentRepository;

// Nested fragments component in sidebar (cannot use livewire for this)
class Fragments extends Component
{
    private FragmentRepository $fragmentRepository;
    private GetShareableFragments $getShareableFragments;

    public ContextOwner $owner;
    public string $contextId;
    private $context;

    public function __construct(string $contextId)
    {
        $this->fragmentRepository = app(FragmentRepository::class);
        $this->getShareableFragments = app(GetShareableFragments::class);

        $this->contextId = $contextId;
        $this->context = ContextModel::find($contextId);
        $this->owner = app(ContextOwnerRepository::class)->findOwner($contextId);
    }

    /**
     * @return Factory|View
     */
    public function render()
    {
        return view('chief-fragments::index', [
            'context' => $this->context,
            'owner' => $this->owner,
            'fragments' => $this->fragmentRepository->getByContext($this->contextId),
            'allowedFragments' => $this->getAllowedFragments(),
            'sharedFragments' => $this->getShareableFragments
                ->excludeAlreadySelected()
                ->filterByTypes($this->owner->allowedFragments())
                ->get($this->contextId),
        ]);
    }

    private function getAllowedFragments(): array
    {
        return array_map(function ($fragmentableClass) {
            return app($fragmentableClass);
        }, $this->owner->allowedFragments());
    }
}
