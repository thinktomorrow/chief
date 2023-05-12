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

    public function getTableRows(): Paginator
    {
        $builder = Asset::with('media')->orderBy('created_at', 'DESC')
            ->select('assets.*');

        if(isset($this->filters['search'])) {
            $builder->whereHas('media', function (Builder $query) {
                $query->where('file_name', 'LIKE', '%' . $this->filters['search'] . '%');
            });
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
