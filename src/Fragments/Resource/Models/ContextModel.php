<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Resource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Relation;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

final class ContextModel extends Model
{
    public $table = "contexts";
    public $guarded = [];
    public $casts = [
        'id' => 'string',
    ];

    public function findFragmentModel($fragmentId): ?FragmentModel
    {
        return $this->fragments()->firstWhere('id', $fragmentId);
    }

    public function fragments(): BelongsToMany
    {
        return $this->belongsToMany(FragmentModel::class, 'context_fragment_lookup', 'context_id', 'fragment_id')
            ->withPivot('order')
            ->with('assetRelation', 'assetRelation.media')
            ->orderBy('context_fragment_lookup.order');
    }

    public function getOwner()
    {
        if (! $this->owner_type || ! $this->owner_id) {
            return null;
        }

        $model_reference = Relation::getMorphedModel($this->owner_type);

        $model = ModelReference::make($model_reference, $this->owner_id)->instance();

        return $model instanceof FragmentModel
            ? app(FragmentFactory::class)->create($model)
            : $model;
    }
}
