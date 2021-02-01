<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Migrate\Legacy\Modules;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Thinktomorrow\AssetLibrary\HasAsset;
use Thinktomorrow\AssetLibrary\AssetTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Thinktomorrow\Chief\Migrate\Legacy\Fragments\HasFragments;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\Viewable;
use Thinktomorrow\Chief\ManagedModels\ManagedModel;
use Thinktomorrow\Chief\Shared\Concerns\Morphable\Morphable;
use Thinktomorrow\Chief\Shared\ModelReferences\ModelReference;
use Thinktomorrow\DynamicAttributes\HasDynamicAttributes;
use Thinktomorrow\Chief\Shared\Concerns\Viewable\ViewableContract;
use Thinktomorrow\Chief\Shared\Concerns\Morphable\MorphableContract;
use Thinktomorrow\Chief\ManagedModels\Assistants\SavingFields;

abstract class Module extends Model implements ManagedModel, HasAsset, MorphableContract, ViewableContract
{
    use Morphable;
    use AssetTrait;
    use HasDynamicAttributes;
    use SoftDeletes;
    use Viewable;
    use HasFragments;
    use SavingFields;

    protected $dynamicKeys = [
        'title',
        'content',
    ];

    public $table = "modules";
    protected $guarded = [];
    protected $baseViewPath;

    final public function __construct(array $attributes = [])
    {
        if (!isset($this->baseViewPath)) {
            $this->baseViewPath = config('chief.base-view-paths.modules', 'modules');
        }

        parent::__construct($attributes);
    }

    public function page()
    {
        return $this->morphTo('page', 'owner_type', 'owner_id');
    }

    /**
     * The page specific ones are the text modules
     * which are added via the page builder
     *
     * @param $query
     */
    public function scopeWithoutPageSpecific($query)
    {
        $query->whereNull('owner_id');
    }

    public function isPageSpecific(): bool
    {
        return !is_null($this->owner_id);
    }

    public function modelReference(): ModelReference
    {
        return new ModelReference(static::class, $this->id);
    }

    public function modelReferenceLabel(): string
    {
        return $this->slug ?? '';
    }

    public function modelReferenceGroup(): string
    {
        $classKey = get_class($this);
        $labelSingular = property_exists($this, 'labelSingular') ? $this->labelSingular : Str::singular($classKey);

        return $labelSingular;
    }
}
