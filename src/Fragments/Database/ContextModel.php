<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Database;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

final class ContextModel extends Model
{
    public $table = "contexts";
    public $guarded = [];

    public static function ownedBy(Model $owner): ?ContextModel
    {
        return static::where('owner_type', $owner->getMorphClass())
                     ->where('owner_id', $owner->id)
                     ->first();
    }

    public static function createForOwner(Model $owner): ContextModel
    {
        return static::create([
            'owner_type' => $owner->getMorphClass(),
            'owner_id' => $owner->id,
        ]);
    }

    public function fragments()
    {
        return $this->belongsToMany(FragmentModel::class, 'context_fragment_lookup', 'context_id', 'fragment_id')
                ->withPivot('order')
                ->with('assetRelation', 'assetRelation.media')
                ->orderBy('context_fragment_lookup.order');
    }

    public function findFragmentModel($fragmentModelId): ?FragmentModel
    {
        return $this->fragments()->firstWhere('id', $fragmentModelId);
    }

    public static function owning(FragmentModel $fragmentModel): Collection
    {
        return static::join('context_fragment_lookup', 'contexts.id', '=', 'context_fragment_lookup.context_id')
            ->where('context_fragment_lookup.fragment_id', $fragmentModel->id)
            ->select(['contexts.*'])
            ->get();
    }
}
