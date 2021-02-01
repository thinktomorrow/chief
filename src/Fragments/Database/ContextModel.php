<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Fragments\Database;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Fragments\FragmentsOwner;

final class ContextModel extends Model
{
    public $table = "context";
    public $guarded = [];

    public static function ownedBy(FragmentsOwner $owner): ?ContextModel
    {
        return static::where('owner_type', $owner->modelReference()->className())
                     ->where('owner_id', $owner->modelReference()->id())
                     ->first();
    }

    public static function createForOwner(FragmentsOwner $owner): ContextModel
    {
        return static::create([
            'owner_type' => $owner->modelReference()->className(),
            'owner_id' => $owner->modelReference()->id(),
        ]);
    }

    public function fragments()
    {
        return $this->hasMany(FragmentModel::class, 'context_id')->orderBy('order');
    }
}
