<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Table;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Thinktomorrow\Chief\Table\Livewire\TableComponent;

class TableServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Livewire::component('chief-wire::table', TableComponent::class);

        $this->app['view']->addNamespace('chief-table', __DIR__.'/UI/views');

        Builder::macro('whereJsonLike', function ($keys, $input, $column = 'values', $table = null, bool $split_by_spaces = true, bool $orClause = false) {

            // Split query up by spaces
            $values = $split_by_spaces
                ? explode(' ', trim($input))
                : (array) $input;

            foreach ($values as $value) {

                $clause = $orClause ? 'orWhere' : 'where';

                $this->{$clause}(function ($query) use ($keys, $column, $value, $table) {
                    foreach ((array) $keys as $key) {
                        $query->orWhereRaw('LOWER(json_extract('.($table ? '`'.$table.'`.' : '').'`'.$column.'`, "$.'.$key.'")) LIKE ?', '%'.trim(strtolower($value)).'%');
                    }
                });
            }
        });

        Builder::macro('orWhereJsonLike', function ($keys, $input, $column = 'values', $table = null, bool $split_by_spaces = true) {
            $this->whereJsonLike($keys, $input, $column, $table, $split_by_spaces, true);
        });

        Builder::macro('whereFragmentsContain', function ($input, $model_type, bool $split_by_spaces = true) {

            // Split query up by spaces
            $values = $split_by_spaces && ! is_array($input)
                ? explode(' ', trim($input))
                : (array) $input;

            $this->orWhere(function ($query) use ($values, $model_type) {
                foreach ($values as $value) {
                    $query->where(function ($_query) use ($value, $model_type) {
                        $_query->orWhereExists(function ($__query) use ($value, $model_type) {
                            $__query->select(DB::raw(1))
                                ->from('contexts')
                                ->whereColumn('contexts.owner_id', $this->from.'.id')
                                ->where('contexts.owner_type', '=', $model_type)
                                ->join('context_fragment_tree', 'contexts.id', '=', 'context_fragment_tree.context_id')
                                ->join('context_fragments', 'context_fragment_tree.child_id', '=', 'context_fragments.id')
                                ->where(function ($query) use ($value) {
                                    $query->whereJsonLike(['title', 'content'], $value, 'data', 'context_fragments');
                                });
                        });
                    });
                }
            });
        });
    }
}
