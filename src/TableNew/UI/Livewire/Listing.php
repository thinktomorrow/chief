<?php

namespace Thinktomorrow\Chief\TableNew\UI\Livewire;

use Illuminate\Contracts\Pagination\Paginator;
use Livewire\Component;
use Livewire\WithPagination;
use Thinktomorrow\Chief\Assets\Components\Gallery;
use Thinktomorrow\Chief\Assets\Livewire\Traits\EmitsToNestables;

class Listing extends Component
{
    use WithPagination;
    use EmitsToNestables;

    protected $table;

    public function getListeners()
    {
        return [
            'assetsDeleted' => 'onAssetsDeleted',
            'assetUpdated-' . $this->getId() => 'onAssetUpdated',
            'filesUploaded' => 'onFilesUploaded',
        ];
    }

    public function booted()
    {
        $this->table = new Gallery($this);
    }

    public function render()
    {
        return view('chief-assets::gallery-component', [
            //
        ]);
    }


    public $filters = [];
    public $sort = null;
    public bool $allowExternalFiles = true;

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function getTableRows(): Paginator
    {
//
//
//        if ($this->sort == 'created_at_asc') {
//            $builder = $builder->orderBy('created_at', 'ASC');
//        } elseif ($this->sort == 'created_at_desc' || ! $this->sort) {
//            $builder = $builder->orderBy('created_at', 'DESC');
//        }

//        return $builder->paginate(24);
    }

    public function getFilters(): array
    {
        return [];
    }

    public function paginationView()
    {
        return 'chief::pagination.livewire-default';
    }
}
