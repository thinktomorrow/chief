<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Thinktomorrow\Chief\Sites\BelongsToSites;
use Thinktomorrow\Chief\Sites\BelongsToSitesDefault;

final class ContextModel extends Model implements BelongsToSites
{
    use BelongsToSitesDefault;

    public $table = 'contexts';

    public $guarded = [];

    public $casts = [
        'id' => 'string',
        'locales' => 'array',
    ];

    public function findFragmentModel($fragmentId): ?FragmentModel
    {
        return $this->fragments()->firstWhere('id', $fragmentId);
    }

    public function rootFragments(): BelongsToMany
    {
        return $this->belongsToMany(FragmentModel::class, 'context_fragment_tree', 'context_id', 'child_id')
            ->withPivot(['parent_id', 'order'])
            ->whereNull('context_fragment_tree.parent_id')
            ->orderBy('context_fragment_tree.order');
    }

    public function fragments(): BelongsToMany
    {
        return $this->belongsToMany(FragmentModel::class, 'context_fragment_tree', 'context_id', 'child_id')
            ->withPivot(['parent_id', 'order'])
            ->orderBy('context_fragment_tree.order');
    }

    public function owner()
    {
        return $this->morphTo('owner')->withoutGlobalScopes();
    }
}
