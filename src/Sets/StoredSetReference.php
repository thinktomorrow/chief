<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Sets;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\FlatReferences\FlatReference;
use Thinktomorrow\Chief\Relations\ActingAsChild;
use Thinktomorrow\Chief\Relations\ActsAsChild;

/**
 * @property $id
 * @property $action
 * @property $key
 * @property $parameters
 */
class StoredSetReference extends Model implements ActsAsChild
{
    use ActingAsChild;

    public $table = 'pagesets'; // TODO: this should change to 'sets' to represent its generic nature.
    public $guarded = [];
    public $timestamps = false;
    public $casts = [
        'parameters' => 'array',
    ];

    /**
     * Run the query and collect the resulting pages into a Set object.
     */
    public function toSet()
    {
        return Set::fromReference($this->toReference());
    }

    public function toReference(): SetReference
    {
        return SetReference::all()->first(function ($setReference) {
            return $setReference->key() == $this->key;
        });
    }

    public function viewkey(): string
    {
        return $this->key;
    }

    public function flatReference(): FlatReference
    {
        return new FlatReference(static::class, $this->id);
    }

    public function flatReferenceLabel(): string
    {
        return $this->toReference()->flatReferenceLabel();
    }

    public function flatReferenceGroup(): string
    {
        return $this->toReference()->flatReferenceGroup();
    }
}
