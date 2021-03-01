<?php

namespace Thinktomorrow\Chief\App\View\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;
use Thinktomorrow\Chief\Fragments\Database\FragmentRepository;
use Thinktomorrow\Chief\Fragments\Fragmentable;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;
use Thinktomorrow\Chief\Managers\Register\Registry;

class Fragments extends Component
{
    private FragmentRepository $fragmentRepository;

    public FragmentsOwner $owner;
    private Collection $fragments;
    private array $allowedFragments;
    private array $sharedFragments;

    public function mount(FragmentsOwner $owner)
    {
        $this->fragmentRepository = app(FragmentRepository::class);
        $this->owner = $owner;
        $this->reload();
    }

    public function render()
    {
        return view('chief::fragments.component.fragments-main', [
            'fragments'        => $this->fragments,
            'allowedFragments' => $this->allowedFragments,
            'sharedFragments'  => $this->sharedFragments,
            'manager'          => app(Registry::class)->manager($this->owner::managedModelKey()),
        ]);
    }

    public function reload()
    {
        $this->fragments = app(FragmentRepository::class)->getByOwner($this->owner->ownerModel())->map(function (Fragmentable $model) {
            return [
                'model'   => $model,
                'manager' => app(Registry::class)->manager($model::managedModelKey()),
            ];
        });

        $this->reloadFragmentSelection();

        $this->emit('fragmentsReloaded');
    }

    private function reloadFragmentSelection()
    {
        // Available fragments
        $this->allowedFragments = array_map(function ($fragmentableClass) {
            $modelClass = app(Registry::class)->modelClass($fragmentableClass::managedModelKey());

            return [
                'manager' => app(Registry::class)->manager($fragmentableClass::managedModelKey()),
                'model'   => new $modelClass(),
            ];
        }, $this->owner->allowedFragments());

        $fragmentModelIds = $this->fragments->map(fn($fragment) => $fragment['model']->fragmentModel())->pluck('id')->toArray();

        $this->sharedFragments = app(FragmentRepository::class)->shared()->reject(function($fragmentable) use($fragmentModelIds){
            return in_array($fragmentable->fragmentModel()->id, $fragmentModelIds);
        })->map(function($fragmentable){
            return [
                'manager' => app(Registry::class)->manager($fragmentable::managedModelKey()),
                'model'   => $fragmentable,
            ];
        })->all();
    }
}
