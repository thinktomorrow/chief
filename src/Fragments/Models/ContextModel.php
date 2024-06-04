<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Models;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class ContextModel extends Model
{
    public $table = "contexts";
    public $guarded = [];
    public $casts = [
        'id' => 'string',
        'locales' => 'array',
    ];

    public function findFragmentModel($fragmentId): ?FragmentModel
    {
        return $this->fragments()->firstWhere('id', $fragmentId);
    }

    public function fragments(): BelongsToMany
    {
        return $this->belongsToMany(FragmentModel::class, 'context_fragment_tree', 'context_id', 'child_id')
            ->withPivot(['parent_id','order'])
            ->orderBy('context_fragment_tree.order');
    }

    /** @deprecated use ContextOwnerRepository::findOwner($contextId) instead */
    public function getOwner()
    {
        throw new Exception('Deprecated method. Use ContextOwnerRepository::findOwner($contextId) instead.');
    }

    public function getLocales(): array
    {
        return $this->locales;
    }
}
