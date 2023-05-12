<?php

namespace Thinktomorrow\Chief\Forms\Fields\File\Livewire;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\Chief\Forms\Fields\File\Components\Gallery;

class GalleryComponent extends Component
{
    use WithPagination;

    public $filters = [];
    public $sort = null;
    public $showAsList = false;

    public Collection $rows;
    protected Gallery $table;

    protected $listeners = [
        'assetsDeleted' => 'onAssetsDeleted',
    ];

    public function mount()
    {
        $this->rows = collect();
    }

    public function booted()
    {
        $this->table = new Gallery($this);
    }

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function showAsList()
    {
        return $this->showAsList = true;
    }

    public function showAsGrid()
    {
        return $this->showAsList = false;
    }

    public function getTableRows(): Paginator
    {
        $builder = Asset::with('media')
            ->select('assets.*');

        if(isset($this->filters['search'])) {
            $builder->whereHas('media', function (Builder $query){
                $query->where('file_name', 'LIKE', '%' . $this->filters['search'] . '%');
            });
        }

        if($this->sort == 'created_at_asc') {
            $builder = $builder->orderBy('created_at', 'ASC');
        } elseif($this->sort == 'created_at_desc' || !$this->sort) {
            $builder = $builder->orderBy('created_at', 'DESC');
        }

        return $builder->paginate(4);
    }

    public function openAssetEdit($assetId)
    {
        $previewFile = PreviewFile::fromAsset(Asset::find($assetId));

        $this->emitDownTo('chief-wire::file-edit', 'openInParentScope', ['previewfile' => $previewFile]);
    }

    public function deleteAsset($assetId)
    {
        $this->emitDownTo('chief-wire::asset-delete', 'openInParentScope', ['assetIds' => [$assetId]]);
    }

    public function onFileUpdated($assetId): void
    {
        // Immediately show the updated values
    }

    public function onAssetsDeleted(array $assetIds): void
    {
        // TODO: show toast of deletion

        $this->callMethod('$refresh');
    }

    public function render()
    {
        return view('chief-form::fields.file.gallery-component', [
            //
        ]);
    }

    private function emitDownTo($name, $event, array $params = [])
    {
        $params['parent_id'] = $this->id;

        $this->emitTo($name, $event, $params);
    }
}

