<?php

namespace Thinktomorrow\Chief\TableNew;

use Thinktomorrow\Chief\TableNew\Filters\SelectFilter;
use Thinktomorrow\Chief\TableNew\Filters\TextFilter;

class Table
{
    private array $filters = [];
    private array $sorters = [];
    private bool $paginate = false;
    private int $paginatePerPage = 10;

    public function filters(array $filters = []): static
    {
        // How to assign: primary, hidden,
        $this->filters = $filters;

        return $this;
    }

    public function sorters(array $sorters = []): static
    {
        // How to assign: primary, hidden,
        $this->sorters = $sorters;

        return $this;
    }

    public function paginate(int $paginatePerPage = 10): static
    {
        $this->paginate = true;
        $this->paginatePerPage = $paginatePerPage;

        return $this;
    }

    public function noPagination(): static
    {
        $this->paginate = false;

        return $this;
    }

    // render via livewire
    // Provide all shizzle to livewire

    //protected function getResource(): string;

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

Table::default()->filters([
    SelectFilter::make('status', function ($query) {
        $query->where('status', 'published');
    })->options([
        'published' => 'Published',
        'draft' => 'Draft',
        'archived' => 'Archived',
    ])->displayAsPrimary()->displayInDropdown(),
    TextFilter::make('title', function ($query, $value) {
        $query->where('title', 'like', '%'.$value.'%');
    }),
])->sorters([
    'title' => [
        'type' => 'text',
    ],
    'created_at' => [
        'type' => 'date',
    ],
])->paginate(20);
