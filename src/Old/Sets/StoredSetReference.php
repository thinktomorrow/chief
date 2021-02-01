<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Old\Sets;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\Chief\PageBuilder\Relations\ActsAsChild;
use Thinktomorrow\Chief\PageBuilder\Relations\ActsAsParent;
use Thinktomorrow\Chief\PageBuilder\Relations\ActingAsChild;

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
    public function toSet(ActsAsParent $parent)
    {
        return Set::fromReference($this->toReference(), $parent);
    }

    public function toReference(): SetReference
    {
        $reference = SetReference::all()->first(function ($setReference) {
            return $setReference->key() == $this->key;
        });

        if (!$reference) {
            throw new \Exception('No query set found by key [' . $this->key . ']. Make sure that this ' . $this->key . ' set is added to the chief.sets config array.');
        }

        return $reference;
    }

    public function modelReference(): ModelReference
    {
        return new ModelReference(static::class, $this->id);
    }

    public function modelReferenceLabel(): string
    {
        return $this->toReference()->modelReferenceLabel();
    }

    public function modelReferenceGroup(): string
    {
        return $this->toReference()->modelReferenceGroup();
    }
}
