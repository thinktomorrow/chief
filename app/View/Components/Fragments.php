<?php

namespace Thinktomorrow\Chief\App\View\Components;

use Illuminate\View\Component;
use Illuminate\Support\Collection;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;

class Fragments extends Component
{
    private FragmentRepository $fragmentRepository;

    public FragmentsOwner $owner;
    private Collection $fragments;
    private array $allowedFragments;

    public function __construct(FragmentsOwner $owner)
    {
        $this->fragmentRepository = app(FragmentRepository::class);
        $this->owner = $owner;
        $this->load();
    }

    public function render()
    {
        return view('chief::components.fragments', [
            'fragments' => $this->fragments,
            'allowedFragments' => $this->allowedFragments,
        ]);
    }

    public function load()
    {
        $this->fragments = app(FragmentRepository::class)->getByOwner($this->owner->ownerModel())->map(function(Fragmentable $model){
            return [
                'model'    => $model,
                'manager'  => app(Registry::class)->manager($model::managedModelKey()),
            ];
        });

        // Available fragments
        $this->allowedFragments = array_map(function($fragmentableClass){
            $modelClass = app(Registry::class)->modelClass($fragmentableClass::managedModelKey());
            return [
                'manager' => app(Registry::class)->manager($fragmentableClass::managedModelKey()),
                'model' => new $modelClass(),
            ];
        }, $this->owner->allowedFragments());
    }
}
