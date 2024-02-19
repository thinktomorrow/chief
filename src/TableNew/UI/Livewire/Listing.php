<?php

namespace Thinktomorrow\Chief\TableNew\UI\Livewire;

use Illuminate\Contracts\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;
use Thinktomorrow\Chief\Assets\Livewire\Traits\EmitsToNestables;
use Thinktomorrow\Chief\Resource\Resource;

abstract class Listing extends Component
{
//    use WithPagination;
//    use EmitsToNestables;

    abstract protected function getResource(): string;

//    public function getListeners()
//    {
//        return [
//
//        ];
//    }
//
//    public function booted()
//    {
////        $this->table = new Gallery($this);
//    }

    public function render()
    {
        return view('chief-table-new::livewire.listing', [
        ]);
    }

//    public function getFilters(): iterable
//    {
//        return [];
//    }
//
//    public function paginationView()
//    {
//        return 'chief::pagination.livewire-default';
//    }
}
