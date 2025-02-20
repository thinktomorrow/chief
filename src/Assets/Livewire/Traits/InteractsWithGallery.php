<?php

namespace Thinktomorrow\Chief\Assets\Livewire\Traits;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Livewire\WithPagination;
use Thinktomorrow\AssetLibrary\Asset;
use Thinktomorrow\AssetLibrary\External\ExternalAssetContract;

trait InteractsWithGallery
{
    use WithPagination;

    public $filters = [];

    public $sort = null;

    public bool $allowExternalFiles = true;

    public function updatedFilters()
    {
        $this->resetPage();
    }

    public function getTableRows(): Paginator
    {
        $builder = Asset::with('media')
            ->select('assets.*');

        if (isset($this->filters['search'])) {
            $builder->whereHas('media', function (Builder $query) {

                $searchTerms = explode(' ', $this->filters['search']);

                foreach ($searchTerms as $searchTerm) {
                    $query->where('file_name', 'LIKE', '%'.$searchTerm.'%');
                }

            });
        }

        if (! $this->allowExternalFiles) {
            $externalTypes = collect(config('thinktomorrow.assetlibrary.types', []))
                ->filter(fn ($class) => $this->isAssetModelExternal($class))
                ->keys()->all();

            $builder->whereNotIn('asset_type', $externalTypes);
        }

        if ($this->sort == 'created_at_asc') {
            $builder = $builder->orderBy('created_at', 'ASC');
        } elseif ($this->sort == 'created_at_desc' || ! $this->sort) {
            $builder = $builder->orderBy('created_at', 'DESC');
        }

        return $builder->paginate(24);
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function paginationView()
    {
        return 'chief::pagination.livewire-default';
    }

    private function isAssetModelExternal(string $modelClass): bool
    {
        return (new \ReflectionClass($modelClass))->implementsInterface(ExternalAssetContract::class);
    }
}
