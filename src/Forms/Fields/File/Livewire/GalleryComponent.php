<?php

namespace Thinktomorrow\Chief\Forms\Fields\File\Livewire;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
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
            $builder->whereHas('media', function (Builder $query){
                $query->where('file_name', 'LIKE', '%' . $this->filters['search'] . '%');
            });
        }

        return $builder->paginate(4);
    }

    public function openFileEdit($assetId)
    {
        $this->emitDownTo('chief-wire::file-edit', 'openInParentScope', ['previewfile_array' => $this->previewFiles[$this->findPreviewFile($fileId)]]);
    }

    public function search()
    {

    }

    public function deleteFile($assetId)
    {
        //
    }

    public function onFileUpdated($assetId): void
    {
        // Immediately show the updated values
    }

    public function render()
    {
        return view('chief-form::fields.file.gallery-component', [
            //
        ]);
    }

    private function emitDownTo($name, $event, array $params)
    {
        $params['parent_id'] = $this->id;

        $this->emitTo($name, $event, $params);
    }
}

