<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Database;

use Illuminate\Database\Eloquent\Model;

final class ContextModel extends Model
{
    public $table = "context";
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
        return $this->hasMany(FragmentModel::class, 'context_id')->orderBy('order');
    }
}
