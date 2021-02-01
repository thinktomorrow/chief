<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Migrate\Legacy;

use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;

/**
 * @property $id
 * @property $action
 * @property $key
 * @property $parameters
 */
class StoredSetReference extends Model
{
    public $table = 'pagesets'; // TODO: this should change to 'sets' to represent its generic nature.
    public $guarded = [];
    public $timestamps = false;
    public $casts = [
        'parameters' => 'array',
    ];

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
